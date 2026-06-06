<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ActionApproveController extends Controller
{
    public function index(): View
    {
        return $this->usersApprove();
    }

    public function usersApprove(): View
    {
        $actor = auth()->user();

        $approvals = DB::table('company_user')
            ->join('users', 'users.id', '=', 'company_user.user_id')
            ->join('companies', 'companies.id', '=', 'company_user.company_id')
            ->select([
                'users.id as user_id',
                'users.first_name',
                'users.second_name',
                'users.email',
                'companies.id as company_id',
                'companies.name as company_name',
                'company_user.role',
                'company_user.status_membership',
            ])
            ->whereIn('company_user.status_membership', ['pending', 'pending_admin'])
            ->when(!$actor->isSuperAdmin(), function ($query) {
                $query->where('company_user.company_id', currentCompany()?->id)
                    ->where('company_user.status_membership', 'pending');
            })
            ->orderByDesc('company_user.updated_at')
            ->paginate(5);

        return view('action-approve.users-approve', compact('approvals'));
    }

    public function showUser(User $user): View
    {
        $actor = auth()->user();
        $companyId = $actor->isSuperAdmin() ? request()->integer('company_id') : currentCompany()?->id;

        $approval = DB::table('company_user')
            ->join('companies', 'companies.id', '=', 'company_user.company_id')
            ->where('company_user.user_id', $user->id)
            ->where('company_user.company_id', $companyId)
            ->whereIn('company_user.status_membership', ['pending', 'pending_admin'])
            ->select('company_user.*', 'companies.name as company_name')
            ->first();

        abort_unless($approval, 404);
        abort_unless($actor->isSuperAdmin() || $approval->status_membership === 'pending', 403);

        return view('action-approve.users-show', compact('user', 'approval'));
    }

    public function approveUser(User $user): RedirectResponse
    {
        $actor = auth()->user();
        $companyId = $actor->isSuperAdmin() ? request()->integer('company_id') : currentCompany()?->id;

        $approval = $this->approvalFor($user, $companyId);

        if ($approval->status_membership === 'pending_admin') {
            abort_unless($actor->isSuperAdmin(), 403);
            $user->companies()->updateExistingPivot($companyId, [
                'status_membership' => 'active',
                'role' => 'admin',
            ]);
        } else {
            $user->companies()->updateExistingPivot($companyId, ['status_membership' => 'active']);
            $user->update(['status_account' => 'active']);
        }

        return redirect()->route('action-approve.users-approve')->with('status', 'Approval accepted.');
    }

    public function rejectUser(User $user): RedirectResponse
    {
        $actor = auth()->user();
        $companyId = $actor->isSuperAdmin() ? request()->integer('company_id') : currentCompany()?->id;
        $approval = $this->approvalFor($user, $companyId);

        if ($approval->status_membership === 'pending_admin') {
            abort_unless($actor->isSuperAdmin(), 403);
            $user->companies()->updateExistingPivot($companyId, ['status_membership' => 'active']);
        } else {
            $user->companies()->updateExistingPivot($companyId, ['status_membership' => 'rejected']);

            $hasActiveMembership = $user->companies()
                ->wherePivotIn('status_membership', ['active', 'pending_admin'])
                ->exists();

            if (!$hasActiveMembership) {
                $user->update(['status_account' => 'rejected']);
            }
        }

        return redirect()->route('action-approve.users-approve')->with('status', 'Approval rejected.');
    }

    public function postsApprove(): View
    {
        $actor = auth()->user();

        $posts = Post::query()
            ->with(['company:id,name', 'user:id,first_name,second_name,email'])
            ->where('status', 'pending')
            ->when(!$actor->isSuperAdmin(), function ($query) {
                $query->where('company_id', currentCompany()?->id);
            })
            ->latest('id')
            ->paginate(5);

        return view('action-approve.posts-approve', compact('posts'));
    }

    public function showPost(Post $post): View
    {
        $post->load(['company:id,name', 'user:id,first_name,second_name,email']);
        return view('action-approve.posts-show', compact('post'));
    }

    public function approvePost(Post $post): RedirectResponse
    {
        $post->update(['status' => 'publish']);
        return redirect()->route('action-approve.posts-show', $post)->with('status', 'Post approved and published.');
    }

    public function rejectPost(Post $post): RedirectResponse
    {
        $post->update(['status' => 'draft']);
        return redirect()->route('action-approve.posts-show', $post)->with('status', 'Post rejected and moved to draft.');
    }

    private function approvalFor(User $user, int $companyId): object
    {
        $approval = DB::table('company_user')
            ->where('user_id', $user->id)
            ->where('company_id', $companyId)
            ->whereIn('status_membership', ['pending', 'pending_admin'])
            ->first();

        abort_unless($approval, 404);
        return $approval;
    }
}
