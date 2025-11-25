<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

/**
 * Handles frontend (Blade) post pages inside a company.
 */
class PostController extends Controller
{
    /**
     * Show list of posts for a company.
     */
    public function index($companyId)
    {
        // Posts will be loaded from /api/main/{id}/posts
        return view('posts.index', compact('companyId'));
    }

    /**
     * Show a single post page.
     */
    public function show($companyId, $postId)
    {
        // Post data fetched via API: /api/main/{id}/posts/{postId}
        return view('posts.show', compact('companyId', 'postId'));
    }
}

