<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * @throws AuthorizationException
     */
    public function store(Request $request, User $user): \Illuminate\Http\JsonResponse
    {
        $this->authorize('update', $user);

        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post = Post::create([
            'company_id' => auth()->user()->company_id,
            'user_id' =>
            'title' => $validated['title'],
            'content' => $validated['content'],
            'status' =>,
            'created_by' =>,
            'updated_by' =>,
            'deleted_by' =>,
        ]);

        return response()->json($post, 201);
    }

    /**
     * @throws AuthorizationException
     */
    public function show(Company $company, Post $post): Post
    {
        $this->authorize('view', $company);

        return $post;
    }

    /**
     * Update the specified resource in storage.
     * @throws AuthorizationException
     */
    public function update(Request $request, Company $company, Post $post): Post
    {
        $this->authorize('update', $company);

        $post->update($request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]));

        return $post;
    }

    /**
     * Remove the specified resource from storage.
     * @throws AuthorizationException
     */
    public function destroy(Company $company, Post $post): \Illuminate\Http\JsonResponse
    {
        $this->authorize('delete', $company);

        $post->delete();

        return response()->json(null, 204);
    }
}
