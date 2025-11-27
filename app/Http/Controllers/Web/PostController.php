<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

/**
 * Handles frontend (Blade) post pages inside a company.
 */
class PostController extends Controller
{
    public function index()
    {
        return view('admin.posts.index');
    }

    public function show($companyId, $postId)
    {
        // Post data fetched via API: /api/main/{id}/posts/{postId}
        return view('posts.show', compact('companyId', 'postId'));
    }
}

