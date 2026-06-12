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
    public function index(Request $request): View
    {
        $this->authorize('viewAny', User::class);

        $actor = auth()->user();

        $users = User::query()
            ->select([
                'users.*',
                'company_user.company_id as membership_company_id',
                'company_user.role as membership_role',
                'company_user.status_membership as membership_status',
                'companies.name as membership_company_name',
            ])
            ->join('company_user', 'company_user.user_id', '=', 'users.id')
            ->join('companies', 'companies.id', '=', 'company_user.company_id')
            ->where('users.status_account', '!=', 'deleted')
            ->when(!$actor->isSuperAdmin(), fn ($query) => $query->where('company_user.company_id', currentCompany()?->id))
            ->when($request->filled('status_membership'), fn ($query) => $query->where('company_user.status_membership', $request->string('status_membership')))
            ->when($request->filled('role'), fn ($query) => $query->where('company_user.role', $request->string('role')))
            ->orderByDesc('users.id')
            ->paginate(5)
            ->withQueryString();

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
                $query->whereHas('companies', fn ($companyQuery) => $companyQuery
                    ->where('companies.id', currentCompany()?->id)
                    ->whereIn('company_user.status_membership', ['active', 'pending_admin']));
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
                $query->whereHas('companies', fn($companyQuery) => $companyQuery
                    ->where('companies.id', currentCompany()?->id)
                    ->whereIn('company_user.status_membership', ['active', 'pending_admin']));
            })
            ->latest('id')
            ->paginate(5);

        return view('admin.users.pending', compact('users'));
    }

    public function show(User $user): View
    {
        $this->authorize('view', $user);

        $user->load('companies:id,name');
        $currentMembership = $this->currentMembership($user);

        return view('admin.users.show', compact('user', 'currentMembership'));
    }

    public function companies(User $user): View
    {
        $this->authorize('view', $user);

        $companies = $user->companies()
            ->where('companies.status_company', 'active')
            ->withPivot('role', 'status_membership')
            ->orderBy('name')
            ->paginate(5);

        return view('admin.users.companies', compact('user', 'companies'));
    }
    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        DB::transaction(function () use ($user) {
            $user->update(['status_account' => 'deleted']);
            DB::table('company_user')
                ->where('user_id', $user->id)
                ->whereIn('status_membership', ['active', 'pending_admin'])
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
        $this->authorize('create', User::class);

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

        $currentMembership = $this->currentMembership($user);

        return view('admin.users.edit', compact('user', 'currentMembership'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        if (!$request->user()->isSuperAdmin()) {
            $company = currentCompany();
            abort_unless($company && $this->currentMembership($user), 404);

            $validated = $request->validate([
                'status_membership' => ['required', Rule::in(['active', 'pending_admin', 'deleted', 'rejected'])],
            ]);

            DB::table('company_user')
                ->where('user_id', $user->id)
                ->where('company_id', $company->id)
                ->update([
                    'status_membership' => $validated['status_membership'],
                    'updated_at' => now(),
                ]);

            return redirect()->route('admin.users.show', $user)
                ->with('success', 'Membership status updated successfully.');
        }

        $validated = $request->validate([
            'first_name' => 'sometimes|required|string|max:50',
            'second_name' => 'sometimes|required|string|max:50',
            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => 'sometimes|nullable|string|max:30',
            'status_account' => ['sometimes', Rule::in(['pending', 'active', 'rejected', 'deleted'])],
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully.');
    }

    public function excludeFromCompany(Request $request, User $user, Company $company): RedirectResponse
    {
        $this->authorize('excludeFromCompany', [$user, $company]);

        DB::table('company_user')
            ->where('user_id', $user->id)
            ->where('company_id', $company->id)
            ->whereIn('status_membership', ['active', 'pending_admin'])
            ->update([
                'status_membership' => 'deleted',
                'updated_at' => now(),
            ]);

        if ($request->user()->id === $user->id) {
            if ((int) $request->session()->get('current_company_id') === $company->id) {
                $request->session()->forget('current_company_id');
            }

            return redirect()->route('dashboard')
                ->with('success', 'You have been excluded from the company.');
        }

        if (!$request->user()->isSuperAdmin()) {
            return redirect()->route('admin.users.index')
                ->with('success', 'User excluded from the company successfully.');
        }

        return back()->with('success', 'User excluded from the company successfully.');
    }

    private function currentMembership(User $user): ?object
    {
        $company = currentCompany();

        if (!$company) {
            return null;
        }

        return DB::table('company_user')
            ->where('user_id', $user->id)
            ->where('company_id', $company->id)
            ->first();
    }

    public function approve(User $user): RedirectResponse
    {
        $this->authorize('approve', $user);

        $user->update(['status_account' => 'active']);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User approved successfully.');

    }
}
