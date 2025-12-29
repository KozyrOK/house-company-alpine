<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return User::query()
            ->orderBy('id', 'desc')
            ->paginate(15);
    }

    public function store(Request $request, Company $company)
    {
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

        $user->companies()->attach($company->id, ['role' => 'user']);

        return response()->json($user, 201);
    }

    public function show(User $user)
    {
        return $user;
    }

    public function update(Request $request, User $user)
    {
        $user->update($request->only('first_name', 'second_name', 'email', 'phone'));
        return $user;
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->noContent();
    }

    public function approve(User $user)
    {
        $user->update(['status_account' => 'active']);
        return response()->json(['message' => 'User approved', 'user' => $user]);
    }
}
