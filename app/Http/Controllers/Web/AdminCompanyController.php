<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdminCompanyController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Company::class);

        $user = auth()->user();
        $companies = Company::query()
            ->when(!$user->isSuperAdmin(), function ($query) use ($user) {
                $query->whereIn('id', $user->adminCompanyIds());
            })
            ->orderBy('name')
            ->paginate(15);

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

    public function logo(Company $company): BinaryFileResponse
    {
        $this->authorize('view', $company);

        $default = public_path('images/default-image-company.jpg');

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
