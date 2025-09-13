<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Post;
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Company $company): \Illuminate\Http\JsonResponse
    {
        $this->authorize('update', $company);

        $post = $company->posts()->create($request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]));

        return response()->json($post, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company, Post $post): Post
    {
        $this->authorize('view', $company);

        return $post;
    }

    /**
     * Update the specified resource in storage.
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
     */
    public function destroy(Company $company, Post $post): \Illuminate\Http\JsonResponse
    {
        $this->authorize('delete', $company);

        $post->delete();

        return response()->json(null, 204);
    }
}
