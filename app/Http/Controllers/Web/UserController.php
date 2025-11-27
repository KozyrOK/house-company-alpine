<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

/**
 * Handles frontend user management pages (admin view).
 * Data is fetched from /api/users or /api/main/{id}/users.
 */
class UserController extends Controller
{
    /**
     * Show user management page (admin).
     */
    public function index()
    {
        return view('admin.users.index');
    }

    /**
     * Show details for a specific user.
     */
    public function show($userId)
    {
        return view('users.show', compact('userId'));
    }
}
