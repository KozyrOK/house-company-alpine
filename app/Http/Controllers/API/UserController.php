<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(int $companyId)
    {
        $this->authorize('viewAny', [User::class, $companyId]);

        return User::whereHas('companies', fn($q) => $q->where('company_id', $companyId))
            ->paginate();
    }

    public function store(Request $request, int $companyId)
    {
        $this->authorize('create', [User::class, $companyId]);

        $validated = $request->validate([
            'first_name'  => 'required|string|max:50',
            'second_name' => 'required|string|max:50',
            'email'       => 'required|string|email|unique:users',
            'password'    => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'first_name'     => $validated['first_name'],
            'second_name'    => $validated['second_name'],
            'email'          => $validated['email'],
            'password'       => Hash::make($validated['password']),
            'status_account' => 'active',
        ]);

        $user->companies()->attach($companyId, ['role' => 'user']);

        return response()->json($user, 201);
    }

    public function show(int $companyId, User $user)
    {
        $this->authorize('view', [$user, $companyId]);
        return $user;
    }

    public function update(Request $request, int $companyId, User $user)
    {
        $this->authorize('update', [$user, $companyId]);

        $user->update($request->only('first_name', 'second_name', 'email', 'phone'));

        return $user;
    }

    public function destroy(int $companyId, User $user)
    {
        $this->authorize('delete', [$user, $companyId]);
        $user->delete();

        return response()->noContent();
    }

    public function approve(int $companyId, User $user)
    {
        $this->authorize('approve', [$user, $companyId]);

        $user->update(['status_account' => 'active']);

        return response()->json(['message' => 'User approved', 'user' => $user]);
    }
}
