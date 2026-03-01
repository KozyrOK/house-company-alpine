<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;

class AdminUserController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', User::class);

        return User::with('companies:id,name')->paginate();
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);

        return $user->load('companies:id,name');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return response()->noContent();
    }
}
