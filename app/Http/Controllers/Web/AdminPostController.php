<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminPostController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Post::class);

        $posts = Post::with(['company:id,name', 'user:id,first_name,second_name'])
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

    public function create(): View
    {
        $user = request()->user();
        $companies = Company::query()
            ->when(!$user->isSuperAdmin(), function ($query) use ($user) {
                $query->whereIn('id', $user->adminCompanyIds());
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

        Post::create([
            'company_id' => $company->id,
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'content' => $validated['content'],
            'status' => $validated['status'] ?? 'pending',
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ]);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post created successfully.');

    }

    public function edit(Post $post): View
    {
        $this->authorize('update', $post);

        $user = request()->user();
        $companies = Company::query()
            ->when(!$user->isSuperAdmin(), function ($query) use ($user) {
                $query->whereIn('id', $user->adminCompanyIds());
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
            abort(403);
        }

        $validated['updated_by'] = $request->user()->id;
        $post->update($validated);

        return redirect()->route('admin.posts.show', $post)
            ->with('success', 'Post updated successfully.');

    }

}
