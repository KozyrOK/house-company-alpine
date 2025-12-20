<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;

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
        return view('admin.users.show', compact('userId'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }
}
