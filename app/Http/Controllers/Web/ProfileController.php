<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

/**
 * Handles user's own profile (account) management pages.
 * CRUD operations for profile use API endpoints under /api/profile.
 */
class ProfileController extends Controller
{
    /**
     * Show user profile page.
     */
    public function edit()
    {
        return view('components.dashboard');
    }

    /**
     * Update profile info (handled by API or form submission).
     */
    public function update()
    {
        // This could later submit data to /api/profile via AJAX
        return back()->with('status', 'Profile updated');
    }

    /**
     * Delete user account (optional).
     */
    public function destroy()
    {
        // Use API endpoint to actually delete
        return redirect()->route('info')->with('status', 'Account deleted');
    }
}

