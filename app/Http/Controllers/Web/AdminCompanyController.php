<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminCompanyController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Company::class);

        $companies = Company::orderBy('name')->paginate(15);

        return view('admin.companies.index', compact('companies'));
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
        ]);

        $company = Company::create($validated);

        return redirect()
            ->route('admin.companies.show', $company)
            ->with('success', 'Company created successfully.');
    }

    public function show(Company $company): View
    {
        $this->authorize('view', $company);

        $company->loadCount(['users', 'posts']);

        return view('admin.companies.show', compact('company'));
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
            'name' => 'required|string|max:255',
        ]);

        $company->update($validated);

        return redirect()
            ->route('admin.companies.show', $company)
            ->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company): RedirectResponse
    {
        $this->authorize('delete', $company);

        $company->delete();

        return redirect()
            ->route('admin.companies.index')
            ->with('success', 'Company deleted successfully.');
    }
}
