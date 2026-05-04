<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
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
            ->with('companies:id,name')
            ->where('status_account', 'pending')
            ->when(!$actor->isSuperAdmin(), function ($query) {
                $query->whereHas('companies', fn ($q) => $q->where('companies.id', currentCompany()?->id));
            })
            ->latest('id')
            ->paginate(15);

        return view('action-approve.users-approve', compact('users'));
    }

    public function postsApprove(): View
    {
        $actor = auth()->user();

        $posts = Post::query()
            ->with(['company:id,name', 'user:id,first_name,second_name'])
            ->where('status', 'pending')
            ->when(!$actor->isSuperAdmin(), function ($query) {
                $query->where('company_id', currentCompany()?->id);
            })
            ->latest('id')
            ->paginate(15);

        return view('action-approve.posts-approve', compact('posts'));
    }
}
