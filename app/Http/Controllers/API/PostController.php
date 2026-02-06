<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request, ?Company $company = null): JsonResponse
    {
        $query = Post::query()->with(['company:id,name', 'user:id,first_name,second_name'])->latest();

        if ($company) {
            $query->where('company_id', $company->id);
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', (int) $request->integer('company_id'));
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', (int) $request->integer('user_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        return response()->json($query->paginate(15));
    }

    public function store(Request $request, ?Company $company = null): JsonResponse
    {
        $validated = $request->validate([
            'company_id' => $company ? 'sometimes|integer|exists:companies,id' : 'required|integer|exists:companies,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'sometimes|in:draft,future,pending,publish,trash',
        ]);

        $targetCompanyId = $company?->id ?? (int) $validated['company_id'];

        $post = Post::create([
            'company_id' => $targetCompanyId,
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'content' => $validated['content'],
            'status' => $request->user()->isSuperAdmin() ? ($validated['status'] ?? 'publish') : ($validated['status'] ?? 'pending'),
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ]);

        return response()->json($post->load(['company:id,name', 'user:id,first_name,second_name']), 201);
    }

    public function show(Request $request, Post $post): JsonResponse
    {
        $company = $request->route('company');

        if ($company && $post->company_id !== $company->id) {
            abort(404);
        }

        return response()->json($post->load(['company:id,name', 'user:id,first_name,second_name']));
    }

    public function update(Request $request, Company $company, Post $post)
    {
        $company = $request->route('company');
        if ($company && $post->company_id !== $company->id) {
            abort(404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'status' => 'sometimes|in:draft,future,pending,publish,trash',
        ]);

        $validated['updated_by'] = $request->user()->id;
        $post->update($validated);

        return response()->json($post->fresh()->load(['company:id,name', 'user:id,first_name,second_name']));
    }

    public function destroy(Request $request, Post $post): JsonResponse
    {
        $company = $request->route('company');
        if ($company && $post->company_id !== $company->id) {
            abort(404);
        }

        $post->update(['deleted_by' => auth()->id()]);
        $post->delete();

        return response()->json([], 204);
    }

    public function approve(Request $request, Post $post): JsonResponse
    {
        $company = $request->route('company');
        if ($company && $post->company_id !== $company->id) {
            abort(404);
        }

        $post->update([
            'status' => 'publish',
            'updated_by' => auth()->id(),
        ]);

        return response()->json($post->fresh()->load(['company:id,name', 'user:id,first_name,second_name']));
    }
}
