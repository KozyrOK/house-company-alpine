<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Post::class);

        $posts = Post::latest()->paginate(15);
        return view('admin.posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        $this->authorize('view', $post);

        return view('admin.posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        return view('admin.posts.edit', compact('post'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Post::class);

        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Post::create([
            'title'   => $validated['title'],
            'content' => $validated['content'],
            'status'  => 'pending',
            'user_id' => $request->user()->id,
        ]);

        return redirect()->route('admin.posts.index');
    }

    public function create()
    {
        $this->authorize('create', Post::class);

        $companies = Company::query()->orderBy('name')->get();

        return view('admin.posts.create', compact('companies'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post->update($validated);

        return redirect()->route('admin.posts.index');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        return redirect()->route('admin.posts.index');
    }
}
