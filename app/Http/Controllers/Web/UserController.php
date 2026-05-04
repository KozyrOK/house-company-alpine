<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        if ($request->routeIs('main.users.index')) {
            $company = currentCompany();
            abort_unless($company, 403);

            $users = $company->users()
                ->wherePivotIn('role', ['user', 'company_head'])
                ->with('companies:id,name')
                ->latest('users.id')
                ->paginate(5);

            return view('user.users.index', compact('users', 'company'));
        }

        $this->authorize('viewAny', User::class);

        $query = User::query()->with('companies:id,name')->latest('id');
        $actor = $request->user();

        if (!$actor->isSuperAdmin()) {
            $company = currentCompany();
            abort_unless($company, 403);
            $query->whereHas('companies', fn ($q) => $q->where('companies.id', $company->id));
        }

        $users = $query->paginate(5);

        return view('admin.users.index', compact('users'));
    }

    public function show(Company $company, $userId): View
    {
        $company = currentCompany() ?? $company;
        $user = $company->users()->findOrFail($userId);

        return view('user.users.show', compact('user', 'company'));
    }

    public function create(): View
    {
        $user = request()->user();

        $companies = $user->isSuperAdmin()
            ? Company::query()->orderBy('name')->get()
            : collect([currentCompany()])->filter();

        return view('admin.users.create', compact('companies'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        $validated = $request->validate([
            'first_name' => 'sometimes|required|string|max:50',
            'second_name' => 'sometimes|required|string|max:50',
            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => 'sometimes|nullable|string|max:30',
            'status_account' => ['sometimes', Rule::in(['pending', 'active', 'blocked'])],
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.show', $user)
            ->with('status', 'User updated successfully');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|integer|exists:companies,id',
            'first_name' => 'required|string|max:50',
            'second_name' => 'required|string|max:50',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:30',
            'role' => ['required', Rule::in(['user', 'company_head', 'admin'])],
        ]);

        $company = Company::query()->findOrFail((int) $validated['company_id']);

        $this->authorize('create', [User::class, $company]);

        $requiresApproval = $request->user()->roleIn($company) === 'company_head';

        $user = User::create([
            'first_name' => $validated['first_name'],
            'second_name' => $validated['second_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'status_account' => $requiresApproval ? 'pending' : 'active',
        ]);

        $user->companies()->attach($company->id, ['role' => $validated['role']]);

        return redirect()->route('admin.users.show', $user)
            ->with('status', $requiresApproval ? 'User created and sent for approval.' : 'User created successfully');
    }

    public function edit(User $user): View
    {
        $this->authorize('update', $user);
        return view('admin.users.edit', compact('user'));
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        $user->update(['deleted_by' => $request->user()->id]);
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('status', 'User deleted successfully');
    }
}
