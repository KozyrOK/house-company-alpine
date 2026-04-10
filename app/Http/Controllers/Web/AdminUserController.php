<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminUserController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', User::class);

        $actor = auth()->user();

        $users = User::query()
            ->with('companies:id,name')
            ->when(!$actor->isSuperAdmin(), function ($query) use ($actor) {
                $query->whereHas('companies', fn ($companyQuery) => $companyQuery->whereIn('companies.id', $actor->adminCompanyIds()));
            })
            ->latest('id')
            ->paginate(15);

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

        DB::transaction(function () use ($user): void {
            Post::query()
                ->where('created_by', $user->id)
                ->update(['created_by' => auth()->id()]);

            Post::query()
                ->where('updated_by', $user->id)
                ->update(['updated_by' => null]);

            Post::query()
                ->where('deleted_by', $user->id)
                ->update(['deleted_by' => null]);

            $user->delete();
        });

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function create(): View
    {
        $companies = auth()->user()->isSuperAdmin()
            ? Company::query()->orderBy('name')->get()
            : Company::query()->whereIn('id', auth()->user()->adminCompanyIds())->orderBy('name')->get();

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
            ->with('success', 'User created successfully.');
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
            'status_account' => ['sometimes', Rule::in(['pending', 'active', 'blocked'])],
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully.');

    }
}
