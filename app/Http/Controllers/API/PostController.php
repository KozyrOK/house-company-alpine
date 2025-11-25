<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Company $company)
    {
        return Post::where('company_id', $company->id)->paginate();
    }

    public function store(Request $request, Company $company)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post = Post::create([
            'company_id' => $company->id,
            'user_id'    => $request->user()->id,
            'title'      => $validated['title'],
            'content'    => $validated['content'],
            'status'     => 'pending',
        ]);

        return response()->json($post, 201);
    }

    public function show(Company $company, Post $post)
    {
        return $post;
    }

    public function update(Request $request, Company $company, Post $post)
    {
        $post->update($request->only('title','content'));
        return $post;
    }

    public function destroy(Company $company, Post $post)
    {
        $post->delete();
        return response()->noContent();
    }

    public function approve(Company $company, Post $post)
    {
        $post->update(['status' => 'publish']);
        return $post;
    }
}
