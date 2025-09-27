<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(int $companyId)
    {
        $this->authorize('viewAny', [Post::class, $companyId]);
        return Post::where('company_id', $companyId)->paginate();
    }

    public function store(Request $request, int $companyId)
    {
        $this->authorize('create', [Post::class, $companyId]);

        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        return Post::create([
            'company_id' => $companyId,
            'user_id'    => $request->user()->id,
            'title'      => $validated['title'],
            'content'    => $validated['content'],
            'status'     => 'pending',
        ]);
    }

    public function show(int $companyId, Post $post)
    {
        $this->authorize('view', [$post, $companyId]);
        return $post;
    }

    public function update(Request $request, int $companyId, Post $post)
    {
        $this->authorize('update', [$post, $companyId]);
        $post->update($request->only('title', 'content'));
        return $post;
    }

    public function destroy(int $companyId, Post $post)
    {
        $this->authorize('delete', [$post, $companyId]);
        $post->delete();
        return response()->noContent();
    }

    public function approve(int $companyId, Post $post)
    {
        $this->authorize('approve', [$post, $companyId]);
        $post->update(['status' => 'publish']);
        return $post;
    }
}
