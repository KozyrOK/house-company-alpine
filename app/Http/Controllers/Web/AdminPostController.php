<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminPostController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Post::class);

        $posts = Post::with(['company:id,name', 'user:id,first_name,last_name'])
            ->latest()
            ->paginate(15);

        return view('admin.posts.index', compact('posts'));
    }

    public function show(Post $post): View
    {
        $this->authorize('view', $post);

        $post->load(['company', 'user']);

        return view('admin.posts.show', compact('post'));
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->authorize('delete', $post);

        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Post deleted successfully.');
    }
}
