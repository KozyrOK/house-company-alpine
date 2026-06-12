<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CompanyController extends Controller
{

    public function select(Request $request): View
    {
        $user = $request->user();

        if ($user->isSuperAdmin()) {
            return view('user.companies.index', ['companies' => Company::orderBy('name')->paginate(5)]);
        }

        $companies = $user->companies()
            ->where('companies.status_company', 'active')
            ->wherePivotIn('status_membership', ['active', 'pending_admin'])
            ->orderBy('name')
            ->get();

        return view('pages.company-select', compact('companies'));
    }

    public function switch(Request $request, Company $company): RedirectResponse
    {
        if ($company->status_company !== 'active' || !$request->user()->belongsToCompany($company->id)) {
            abort(403);
        }

        $request->session()->put('current_company_id', $company->id);

        $role = $request->user()->roleIn($company);
        $targetRoute = in_array($role, ['admin'], true) ? 'admin.index' : 'main.index';

        return redirect()->route($targetRoute)->with('success', 'Current company switched.');
    }

    public function current(Request $request): RedirectResponse|View
    {
        if ($request->user()->isSuperAdmin()) {
            return redirect()->route('admin.companies.index');
        }

        $company = currentCompany();

        if (!$company) {
            return redirect()->route('company.select');
        }

        $company->loadCount(['users', 'posts']);
        $adminCandidates = $company->users()
            ->wherePivot('status_membership', 'active')
            ->wherePivotIn('role', ['user', 'company_head'])
            ->orderBy('first_name')
            ->get(['users.id', 'first_name', 'second_name', 'email']);

        return view('user.companies.show', compact('company', 'adminCandidates'));
    }

    public function requestMembership(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
        ]);

        $company = Company::query()
            ->where('status_company', 'active')
            ->findOrFail((int) $validated['company_id']);

        $existing = DB::table('company_user')
            ->where('user_id', $request->user()->id)
            ->where('company_id', $company->id)
            ->first();

        if ($existing && in_array($existing->status_membership, ['active', 'pending', 'pending_admin'], true)) {
            return redirect()->route('dashboard')->with('status', 'Company request already exists.');
        }

        if ($existing) {
            DB::table('company_user')
                ->where('user_id', $request->user()->id)
                ->where('company_id', $company->id)
                ->update([
                    'status_membership' => 'pending',
                    'role' => 'user',
                    'updated_at' => now(),
                ]);
        } else {
            $request->user()->companies()->attach($company->id, [
                'role' => 'user',
                'status_membership' => 'pending',
            ]);
        }

        if ($request->user()->status_account === 'rejected') {
            $request->user()->update(['status_account' => 'pending']);
        }

        return redirect()->route('dashboard')->with('status', 'Company request sent for approval.');
    }

    public function requestList(Request $request): View
    {
        $companies = $request->user()->companies()
            ->wherePivotIn('status_membership', ['pending', 'rejected'])
            ->orderBy('name')
            ->paginate(10);

        return view('pages.request-list', compact('companies'));
    }

    public function deleteMembershipRequest(Request $request, Company $company): RedirectResponse
    {
        $membership = DB::table('company_user')
            ->where('user_id', $request->user()->id)
            ->where('company_id', $company->id)
            ->whereIn('status_membership', ['pending', 'rejected'])
            ->first();

        abort_unless($membership, 404);

        DB::table('company_user')
            ->where('user_id', $request->user()->id)
            ->where('company_id', $company->id)
            ->update([
                'status_membership' => 'deleted',
                'updated_at' => now(),
            ]);

        return redirect()->route('company.request-list')
            ->with('status', 'Membership request deleted.');
    }

    public function requestAdmin(Request $request): RedirectResponse
    {
        $company = currentCompany();
        abort_unless($company && $request->user()->hasRole('admin', $company->id), 403);

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $member = DB::table('company_user')
            ->where('company_id', $company->id)
            ->where('user_id', $validated['user_id'])
            ->where('status_membership', 'active')
            ->whereIn('role', ['user', 'company_head'])
            ->first();

        abort_unless($member, 404);

        DB::table('company_user')
            ->where('company_id', $company->id)
            ->where('user_id', $validated['user_id'])
            ->update(['status_membership' => 'pending_admin', 'updated_at' => now()]);

        return redirect()->route('company.current')->with('status', 'Admin role request sent for superadmin approval.');
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Company::class);

        $status = $request->input('status_company', 'active');

        $query = Company::query()
            ->when(in_array($status, ['active', 'deleted'], true), fn ($companyQuery) => $companyQuery->where('status_company', $status))
            ->orderBy('name');

        if (!$request->user()->isSuperAdmin()) {
            $query->whereIn('id', [$request->session()->get('current_company_id')]);
        }

        if ($request->filled('city')) {
            $query->where('city', $request->string('city'));
        }

        $companies = $query->paginate(5)->withQueryString();

        if ($request->routeIs('main.companies.index')) {
            return view('user.companies.index', compact('companies'));
        }

        return view('admin.companies.index', compact('companies'));
    }

    public function show(Request $request, Company $company): View
    {
        $this->authorize('view', $company);

        $company->loadCount(['users', 'posts']);

        return view('admin.companies.show', compact('company'));
    }

    public function create(): View
    {
        $this->authorize('create', Company::class);

        return view('admin.companies.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Company::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
        ]);

        $company = Company::create($validated);

        $company->users()->syncWithoutDetaching([
            $request->user()->id => ['role' => 'superadmin'],
        ]);

        return redirect()->route('admin.companies.show', $company)
            ->with('status', 'Company created successfully');
    }

    public function edit(Company $company): View
    {
        $this->authorize('update', $company);

        return view('admin.companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company): RedirectResponse
    {
        $this->authorize('update', $company);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|nullable|string|max:255',
            'city' => 'sometimes|nullable|string|max:255',
            'description' => 'sometimes|nullable|string|max:2000',
        ]);

        $company->update($validated);

        return redirect()->route('admin.companies.show', $company)
            ->with('status', 'Company updated successfully');
    }

    public function destroy(Request $request, Company $company): RedirectResponse
    {
        $this->authorize('delete', $company);

        $company->posts()->where('status', '!=', 'trash')->update(['deleted_by' => $request->user()->id, 'status' => 'trash']);
        DB::table('company_user')
            ->where('company_id', $company->id)
            ->update(['status_membership' => 'deleted']);
        $company->update(['deleted_by' => $request->user()->id, 'status_company' => 'deleted']);

        return redirect()->route('admin.companies.index')
            ->with('status', 'Company deleted successfully');
    }

    public function logo(Company $company): BinaryFileResponse
    {
        $this->authorize('view', $company);

        $default = public_path('images/default-image-company.webp');

            $logoPath = $company->getAttribute('logo_path');

            if (!$logoPath || !Storage::disk('public')->exists($logoPath)) {
                return response()->file($default);
            }

            $path = Storage::disk('public')->path($logoPath);

            if (!file_exists($path)) {
                return response()->file($default);
            }

            return response()->file($path);
    }
}
