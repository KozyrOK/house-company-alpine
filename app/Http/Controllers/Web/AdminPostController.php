<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminPostController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Post::class);

        $user = request()->user();
        $posts = Post::with(['company:id,name', 'user:id,first_name,second_name'])
            ->when(!$user->isSuperAdmin(), function ($query) use ($user) {
                $query->where('company_id', currentCompany()?->id);
            })
            ->latest()
            ->paginate(5);

        return view('admin.posts.index', compact('posts'));
    }

    public function trash(): View
    {
        $this->authorize('viewAny', Post::class);

        $user = request()->user();
        $posts = Post::onlyTrashed()
            ->with(['company:id,name', 'user:id,first_name,second_name'])
            ->when(!$user->isSuperAdmin(), function ($query) use ($user) {
                $query->where('company_id', currentCompany()?->id);
            })
            ->latest('deleted_at')
            ->paginate(5);

        return view('admin.posts.trash', compact('posts'));
    }

    public function pending(): View
    {
        $this->authorize('viewAny', Post::class);

        $user = request()->user();
        $posts = Post::with(['company:id,name', 'user:id,first_name,second_name'])
            ->where('status', 'pending')
            ->when(!$user->isSuperAdmin(), function ($query) {
                $query->where('company_id', currentCompany()?->id);
            })
            ->latest()
            ->paginate(5);

        return view('admin.posts.pending', compact('posts'));
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

        $post->update(['deleted_by' => auth()->id()]);
        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Post deleted successfully.');
    }

    public function restore(int $post): RedirectResponse
    {
        $model = Post::onlyTrashed()->findOrFail($post);
        $this->authorize('restore', $model);

        $model->restore();
        $model->update(['deleted_by' => null]);

        return redirect()
            ->route('admin.posts.trash')
            ->with('success', 'Post restored successfully.');
    }

    public function create(): View
    {
        $user = request()->user();
        $companies = Company::query()
            ->when(!$user->isSuperAdmin(), function ($query) use ($user) {
                $query->where('id', currentCompany()?->id);
            })
            ->orderBy('name')
            ->get();

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

        $actor = $request->user();
        $status = $validated['status'] ?? 'pending';

        if ($actor->hasRole(['admin', 'company_head'], $company->id) || $actor->isSuperAdmin()) {
            $status = $validated['status'] ?? 'publish';
        }

        Post::create([
            'company_id' => $company->id,
            'user_id' => $actor->id,
            'title' => $validated['title'],
            'content' => $validated['content'],
            'status' => $status,
            'created_by' => $actor->id,
            'updated_by' => $actor->id,
        ]);

        return redirect()->route('admin.posts.index')
            ->with('success', $status === 'pending'
                ? 'Post created and sent for approval.'
                : 'Post created successfully.');

    }

    public function edit(Post $post): View
    {
        $this->authorize('update', $post);

        $user = request()->user();
        $companies = Company::query()
            ->when(!$user->isSuperAdmin(), function ($query) use ($user) {
                $query->where('id', currentCompany()?->id);
            })
            ->orderBy('name')
            ->get();

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

        $companyId = (int) ($validated['company_id'] ?? $post->company_id);
        if (!$request->user()->isAdminOrHigher($companyId)) {
            $validated['status'] = 'pending';
        }

        $validated['updated_by'] = $request->user()->id;
        $post->update($validated);

        return redirect()->route('admin.posts.show', $post)
            ->with('success', 'Post updated successfully.');
    }

    public function approve(Post $post): RedirectResponse
    {
        $this->authorize('approve', $post);

        $post->update([
            'status' => 'publish',
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('admin.posts.show', $post)
            ->with('success', 'Post approved successfully.');
    }

}
