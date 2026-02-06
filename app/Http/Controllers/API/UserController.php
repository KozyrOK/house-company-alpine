<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $actor = $request->user();

        $query = User::query()->with('companies:id,name')->latest('id');

        if (!$actor->isSuperAdmin()) {
            $companyIds = $actor->companies()->pluck('companies.id');
            $query->whereHas('companies', fn ($q) => $q->whereIn('companies.id', $companyIds));
        }

        if ($request->filled('company_id')) {
            $companyId = (int) $request->integer('company_id');
            $query->whereHas('companies', fn ($q) => $q->where('companies.id', $companyId));
        }

        if ($request->filled('status_account')) {
            $query->where('status_account', $request->string('status_account'));
        }

        return response()->json($query->paginate(15));
    }

    public function store(Request $request, Company $company): JsonResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'second_name' => 'required|string|max:50',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:30',
            'role' => ['sometimes', Rule::in(['user', 'company_head', 'admin'])],
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'second_name' => $validated['second_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'status_account' => 'pending',
        ]);

        $user->companies()->attach($company->id, ['role' => $validated['role'] ?? 'user']);

        return response()->json($user->load('companies:id,name'), 201);
    }

    public function show(Request $request, User $user): JsonResponse
    {
        $company = $request->route('company');

        if ($company && !$user->belongsToCompany($company->id)) {
            abort(404);
        }

        return response()->json($user->load('companies:id,name'));
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $company = $request->route('company');
        if ($company && !$user->belongsToCompany($company->id)) {
            abort(404);
        }

        $validated = $request->validate([
            'first_name' => 'sometimes|required|string|max:50',
            'second_name' => 'sometimes|required|string|max:50',
            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => 'sometimes|nullable|string|max:30',
            'status_account' => ['sometimes', Rule::in(['pending', 'active', 'blocked'])],
        ]);

        $user->update($validated);

        if ($company && $request->filled('role')) {
            $request->validate([
                'role' => ['required', Rule::in(['user', 'company_head', 'admin', 'superadmin'])],
            ]);

            $user->companies()->updateExistingPivot($company->id, ['role' => $request->string('role')->toString()]);
        }

        return response()->json($user->fresh()->load('companies:id,name'));
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        $company = $request->route('company');

        if ($company) {
            $user->companies()->detach($company->id);

            return response()->json([], 204);
        }

        $user->delete();

        return response()->json([], 204);
    }

    public function approve(Request $request, User $user): JsonResponse
    {
        $company = $request->route('company');

        if ($company && !$user->belongsToCompany($company->id)) {
            abort(404);
        }

        $user->update(['status_account' => 'active']);

        return response()->json([
            'message' => 'User approved',
            'user' => $user->fresh()->load('companies:id,name'),
        ]);
    }
}
