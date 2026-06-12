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
            $company = currentCompany();
            abort_unless($company, 403);

            $this->authorize('view', $company);

            $posts = $company->posts()
                ->where('status', '!=', 'trash')
                ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')), fn ($query) => $query->where('status', '!=', 'trash'))
                ->latest()
                ->paginate(5)
                ->withQueryString();

            return view('user.posts.index', compact('company', 'posts'));
        }

        $this->authorize('viewAny', Post::class);

        $query = Post::query()->with(['company:id,name', 'user:id,first_name,second_name'])->latest();
        $user = $request->user();

        if (!$user->isSuperAdmin()) {
            $currentCompany = currentCompany();
            abort_unless($currentCompany, 403);
            $query->where('company_id', $currentCompany->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        } else {
            $query->where('status', '!=', 'trash');
        }

        if ($user->isSuperAdmin() && $request->filled('company_id')) {
            $query->where('company_id', $request->integer('company_id'));
        }

        $posts = $query->paginate(5)->withQueryString();

        return view('admin.posts.index', compact('posts'));
    }

    public function show(Post $post): View
    {
        $this->authorize('view', $post);

        $post->load(['company:id,name', 'user:id,first_name,second_name']);
        $company = currentCompany();

        return view('user.posts.show', compact('post', 'company'));
    }

    public function trash(Request $request): View
    {
        $company = currentCompany();
        abort_unless($company, 403);
        $this->authorize('viewAny', Post::class);

        $posts = Post::query()
            ->where('company_id', $company->id)
            ->where('status', 'trash')
            ->with(['company:id,name', 'user:id,first_name,second_name'])
            ->latest('updated_at')
            ->paginate(5);

        return view('user.posts.trash', compact('posts', 'company'));
    }

    public function restore(int $post): RedirectResponse
    {
        $model = Post::query()->where('status', 'trash')->findOrFail($post);
        $this->authorize('restore', $model);

        $model->update(['deleted_by' => null, 'status' => 'draft', 'updated_by' => auth()->id()]);

        return redirect()->route('main.posts.trash')->with('success', 'Post restored successfully.');
    }

    public function create(): View
    {
        $user = request()->user();

        $companies = $user->isSuperAdmin()
            ? Company::query()->orderBy('name')->get()
            : collect([currentCompany()])->filter();

        $view = request()->routeIs('main.*') ? 'user.posts.create' : 'admin.posts.create';

        return view($view, compact('companies'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_id' => $request->routeIs('main.*') ? 'nullable|integer|exists:companies,id' : 'required|integer|exists:companies,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'sometimes|in:draft,future,pending,publish,trash',
        ]);

        $company = $request->routeIs('main.*')
            ? currentCompany()
            : Company::query()->findOrFail((int) $validated['company_id']);
        abort_unless($company, 403);

        $this->authorize('create', [Post::class, $company]);

        $role = $request->user()->roleIn($company);
        $status = in_array($role, ['admin', 'company_head'], true) || $request->user()->isSuperAdmin()
            ? ($validated['status'] ?? 'publish')
            : 'pending';

        Post::create([
            'company_id' => $company->id,
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'content' => $validated['content'],
            'status' => $status,
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ]);

        $route = $request->routeIs('main.*') ? 'main.posts.index' : 'admin.posts.index';

        return redirect()->route($route)
            ->with('status', $status === 'pending' ? 'Post created and sent for approval.' : 'Post created successfully');
    }

    public function edit(Post $post): View
    {
        $this->authorize('update', $post);

        $user = request()->user();
        $companies = $user->isSuperAdmin()
            ? Company::query()->orderBy('name')->get()
            : collect([currentCompany()])->filter();

        $view = request()->routeIs('main.*') ? 'user.posts.edit' : 'admin.posts.edit';

        return view($view, compact('post', 'companies'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'company_id' => $request->routeIs('main.*') ? 'sometimes|nullable|integer|exists:companies,id' : 'sometimes|required|integer|exists:companies,id',
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'status' => 'sometimes|in:draft,future,pending,publish,trash',
        ]);

        if ($request->routeIs('main.*')) {
            $validated['company_id'] = currentCompany()?->id;
        }

        if (!$request->user()->isSuperAdmin() && !$request->user()->hasRole(['admin', 'company_head'], (int) ($validated['company_id'] ?? $post->company_id))) {
            $validated['status'] = 'pending';
        }

        $validated['updated_by'] = $request->user()->id;
        $post->update($validated);

        $route = $request->routeIs('main.*') ? 'main.posts.show' : 'admin.posts.show';

        return redirect()->route($route, $post)
            ->with('status', 'Post updated successfully');
    }

    public function destroy(Request $request, Post $post): RedirectResponse
    {
        $this->authorize('delete', $post);

        $route = $request->routeIs('main.*') ? 'main.posts.index' : 'admin.posts.index';

        return redirect()->route($route)
            ->with('status', 'Post deleted successfully');
    }
}
