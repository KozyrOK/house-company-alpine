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
        $this->authorize('viewAny', User::class);

        $query = User::query()->with('companies:id,name')->latest('id');

        if ($request->filled('company_id')) {
            $companyId = (int) $request->integer('company_id');
            $query->whereHas('companies', fn ($q) => $q->where('companies.id', $companyId));
        }

        if ($request->filled('status_account')) {
            $query->where('status_account', $request->string('status_account'));
        }

        $users = $query->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $this->authorize('view', $user);

        $user->load('companies:id,name');

        return view('admin.users.show', compact('user'));
    }

    public function create(): View
    {
        $companies = Company::query()->orderBy('name')->get();

        return view('admin.users.create', compact('companies'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        $companies = Company::query()->orderBy('name')->get();

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

        $user = User::create([
            'first_name' => $validated['first_name'],
            'second_name' => $validated['second_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'status_account' => 'pending',
        ]);

        $user->companies()->attach($company->id, ['role' => $validated['role']]);

        return redirect()->route('admin.users.show', $user)
            ->with('status', 'User created successfully');
    }

    public function edit(User $user): View
    {
        $this->authorize('update', $user);

        $companies = Company::query()->orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'companies'));
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('status', 'User deleted successfully');
    }
}
