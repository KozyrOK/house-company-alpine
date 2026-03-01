<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminUserController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', User::class);

        $users = User::with('companies:id,name')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $this->authorize('view', $user);

        $user->load('companies:id,name');

        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
