<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class AdminPostController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Post::class);

        return Post::with(['company:id,name', 'user:id,first_name,second_name'])
                ->latest()
                ->paginate();
    }

    public function show(Post $post)
    {
        $this->authorize('view', $post);

        return $post->load(['company','user']);
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'status' => 'sometimes|in:draft,future,pending,publish,trash',
        ]);

        $validated['updated_by'] = $request->user()->id;
        $post->update($validated);

        return response()->json($post->fresh()->load(['company:id,name', 'user:id,first_name,second_name']));
    }

    public function approve(Request $request, Post $post)
    {
        $this->authorize('approve', $post);

        $post->update([
            'status' => 'publish',
            'updated_by' => $request->user()->id,
        ]);

        return response()->json($post->fresh()->load(['company:id,name', 'user:id,first_name,second_name']));
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->update(['deleted_by' => auth()->id()]);
        $post->delete();

        return response()->noContent();
    }
}
