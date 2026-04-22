<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CompanyController extends Controller
{

    public function select(Request $request): View
    {
        $user = $request->user();

        if ($user->isSuperAdmin()) {
            return view('user.companies.index', ['companies' => Company::orderBy('name')->paginate(15)]);
        }

        $companies = $user->companies()->orderBy('name')->get();

        return view('pages.company-select', compact('companies'));
    }

    public function switch(Request $request, Company $company): RedirectResponse
    {
        if (!$request->user()->isSuperAdmin() && !$request->user()->belongsToCompany($company->id)) {
            abort(403);
        }

        $request->session()->put('current_company_id', $company->id);

        return back()->with('success', 'Current company switched.');
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

        return view('user.companies.show', compact('company'));
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Company::class);

        $query = Company::query()->orderBy('name');

        if (!$request->user()->isSuperAdmin()) {
            $query->whereIn('id', [$request->session()->get('current_company_id')]);
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->string('city') . '%');
        }

        $companies = $query->paginate(15);

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

        $company->update(['deleted_by' => $request->user()->id]);
        $company->delete();

        return redirect()->route('admin.companies.index')
            ->with('status', 'Company deleted successfully');
    }

    public function logo(Company $company): BinaryFileResponse
    {
        $this->authorize('view', $company);

        $default = public_path('images/default-image-company.webp');

        if (!$company->logo_path || !Storage::disk('public')->exists($company->logo_path)) {
            return response()->file($default);
        }

        $path = Storage::disk('public')->path($company->logo_path);

        if (!file_exists($path)) {
            return response()->file($default);
        }

        return response()->file($path);
    }
}
