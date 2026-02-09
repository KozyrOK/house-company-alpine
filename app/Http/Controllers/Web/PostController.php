<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        if ($request->routeIs('main.posts.index')) {
            $company = $request->route('company');

            $this->authorize('view', $company);

            $posts = $company->posts()
                ->with(['user:id,first_name,second_name'])
                ->latest()
                ->paginate(15);

            return view('user.posts.index', compact('company', 'posts'));
        }

        $this->authorize('viewAny', Post::class);

        $query = Post::query()->with(['company:id,name', 'user:id,first_name,second_name'])->latest();

        if ($request->filled('company_id')) {
            $query->where('company_id', (int) $request->integer('company_id'));
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', (int) $request->integer('user_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date('date_to'));
        }

        $posts = $query->paginate(15);

        return view('admin.posts.index', compact('posts'));
    }

    public function show(Post $post): View
    {
        $this->authorize('view', $post);

        if (request()->routeIs('main.posts.show')) {
            $company = request()->route('company');

            if ($company && $post->company_id !== $company->id) {
                abort(404);
            }

            $post->load(['company:id,name', 'user:id,first_name,second_name']);

            return view('user.posts.show', compact('post', 'company'));
        }

        $post->load(['company:id,name', 'user:id,first_name,second_name']);

        return view('admin.posts.show', compact('post'));
    }

    public function create(): View
    {
        $this->authorize('create', Post::class);

        $companies = Company::query()->orderBy('name')->get();

        return view('admin.posts.create', compact('companies'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Post::class);

        $validated = $request->validate([
            'company_id' => 'required|integer|exists:companies,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'sometimes|in:draft,future,pending,publish,trash',
        ]);

        Post::create([
            'company_id' => (int) $validated['company_id'],
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'content' => $validated['content'],
            'status' => $validated['status'] ?? 'pending',
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ]);

        return redirect()->route('admin.posts.index')
            ->with('status', 'Post created successfully');
    }

    public function edit(Post $post): View
    {
        $this->authorize('update', $post);

        $companies = Company::query()->orderBy('name')->get();

        return view('admin.posts.edit', compact('post', 'companies'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'company_id' => 'sometimes|required|integer|exists:companies,id',
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'status' => 'sometimes|in:draft,future,pending,publish,trash',
        ]);

        $validated['updated_by'] = $request->user()->id;
        $post->update($validated);

        return redirect()->route('admin.posts.show', $post)
            ->with('status', 'Post updated successfully');
    }

    public function destroy(Request $request, Post $post): RedirectResponse
    {
        $this->authorize('delete', $post);

        $post->update(['deleted_by' => $request->user()->id]);

        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('status', 'Post deleted successfully');
    }
}
