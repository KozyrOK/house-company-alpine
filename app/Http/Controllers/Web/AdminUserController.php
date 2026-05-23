<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', User::class);

        $actor = auth()->user();

        $users = User::query()
            ->where('status_account', '!=', 'deleted')
            ->with('companies:id,name')
            ->when(!$actor->isSuperAdmin(), function ($query) use ($actor) {
                $query->whereHas('companies', fn($companyQuery) => $companyQuery->where('companies.id', currentCompany()?->id));
            })
            ->latest('id')
            ->paginate(5);

        return view('admin.users.index', compact('users'));
    }

    public function trash(): View
    {
        $this->authorize('viewAny', User::class);

        $actor = auth()->user();

        $users = User::query()
            ->where('status_account', 'deleted')
            ->with('companies:id,name')
            ->when(!$actor->isSuperAdmin(), function ($query) use ($actor) {
                $query->whereHas('companies', fn ($companyQuery) => $companyQuery->where('companies.id', currentCompany()?->id));
            })
            ->latest('updated_at')
            ->paginate(5);

        return view('admin.users.trash', compact('users'));
    }

    public function pending(): View
    {
        $this->authorize('viewAny', User::class);

        $actor = auth()->user();

        $users = User::query()
            ->where('status_account', '!=', 'deleted')
            ->with('companies:id,name')
            ->where('status_account', 'pending')
            ->when(!$actor->isSuperAdmin(), function ($query) {
                $query->whereHas('companies', fn($companyQuery) => $companyQuery->where('companies.id', currentCompany()?->id));
            })
            ->latest('id')
            ->paginate(5);

        return view('admin.users.pending', compact('users'));
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

        DB::transaction(function () use ($user) {
            $user->update(['status_account' => 'deleted']);
            DB::table('company_user')
                ->where('user_id', $user->id)
                ->where('status_membership', 'active')
                ->update(['status_membership' => 'deleted']);
        });

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function restore(int $user): RedirectResponse
    {
        $model = User::query()->where('status_account', 'deleted')->findOrFail($user);
        $this->authorize('restore', $model);

        DB::transaction(function () use ($model) {
            $model->update(['status_account' => 'active']);
            DB::table('company_user')
                ->where('user_id', $model->id)
                ->where('status_membership', 'deleted')
                ->update(['status_membership' => 'active']);
        });

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User restored successfully.');
    }

    public function create(): View
    {
        $companies = auth()->user()->isSuperAdmin()
            ? Company::query()->orderBy('name')->get()
            : collect([currentCompany()])->filter();

        return view('admin.users.create', compact('companies'));
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

        $actor = auth()->user();
        $requiresApproval = $actor->hasRole('company_head', $company->id) && !$actor->hasRole('admin', $company->id);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'second_name' => $validated['second_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'status_account' => $requiresApproval ? 'pending' : 'active',
        ]);

        $user->companies()->attach($company->id, ['role' => $validated['role'], 'status_membership' => $requiresApproval ? 'pending' : 'active']);

        return redirect()->route('admin.users.show', $user)
            ->with('success', $requiresApproval
                ? 'User created and sent for approval.'
                : 'User created successfully.');
    }

    public function edit(User $user): View
    {
        $this->authorize('update', $user);

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        $validated = $request->validate([
            'first_name' => 'sometimes|required|string|max:50',
            'second_name' => 'sometimes|required|string|max:50',
            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => 'sometimes|nullable|string|max:30',
            'status_account' => ['sometimes', Rule::in(['pending', 'active', 'deleted'])],
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully.');
    }

    public function approve(User $user): RedirectResponse
    {
        $this->authorize('approve', $user);

        $user->update(['status_account' => 'active']);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User approved successfully.');

    }
}
