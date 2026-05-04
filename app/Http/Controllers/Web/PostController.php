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
                ->with(['user:id,first_name,second_name'])
                ->latest()
                ->paginate(5);

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
        }

        $posts = $query->paginate(5);

        return view('admin.posts.index', compact('posts'));
    }

    public function show(Company $company, Post $post): View
    {
        $this->authorize('view', $post);

        $post->load(['company:id,name', 'user:id,first_name,second_name']);

        return view('user.posts.show', compact('post', 'company'));
    }

    public function create(): View
    {
        $user = request()->user();

        $companies = $user->isSuperAdmin()
            ? Company::query()->orderBy('name')->get()
            : collect([currentCompany()])->filter();

        return view('admin.posts.create', compact('companies'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|integer|exists:companies,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'sometimes|in:draft,future,pending,publish,trash',
        ]);

        $company = Company::query()->findOrFail((int) $validated['company_id']);
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

        return redirect()->route('admin.posts.index')
            ->with('status', $status === 'pending' ? 'Post created and sent for approval.' : 'Post created successfully');
    }

    public function edit(Post $post): View
    {
        $this->authorize('update', $post);

        $user = request()->user();
        $companies = $user->isSuperAdmin()
            ? Company::query()->orderBy('name')->get()
            : collect([currentCompany()])->filter();

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
