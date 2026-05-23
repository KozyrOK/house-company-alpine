<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ActionApproveController extends Controller
{
    public function index(): View
    {
        return view('pages.action-approve');
    }

    public function usersApprove(): View
    {
        $actor = auth()->user();

        $users = User::query()
            ->with(['companies' => fn($q) => $q->withPivot('role', 'status_membership')])
            ->whereHas('companies', function ($q) use ($actor) {
                $q->where('company_user.status_membership', 'pending');
                if (!$actor->isSuperAdmin()) {
                    $q->where('companies.id', currentCompany()?->id);
                }
            })
            ->latest('id')
            ->paginate(5);

        return view('action-approve.users-approve', compact('users'));
    }

    public function showUser(User $user): View
    {
        $user->load(['companies' => fn($q) => $q->withPivot('role', 'status_membership')]);
        return view('action-approve.users-show', compact('user'));
    }

    public function approveUser(User $user): RedirectResponse
    {
        $actor = auth()->user();
        $companyId = $actor->isSuperAdmin() ? request()->integer('company_id') : currentCompany()?->id;

        $user->companies()->updateExistingPivot($companyId, ['status_membership' => 'active']);

        return redirect()->route('action-approve.users-show', $user)->with('status', 'User membership approved.');
    }

    public function rejectUser(User $user): RedirectResponse
    {
        $actor = auth()->user();
        $companyId = $actor->isSuperAdmin() ? request()->integer('company_id') : currentCompany()?->id;

        $user->companies()->updateExistingPivot($companyId, ['status_membership' => 'rejected']);

        return redirect()->route('action-approve.users-show', $user)->with('status', 'User membership rejected.');
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

}
