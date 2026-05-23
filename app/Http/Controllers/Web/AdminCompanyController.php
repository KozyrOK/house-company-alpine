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

class AdminCompanyController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Company::class);

        $user = auth()->user();
        $companies = Company::query()
            ->where('status_company', 'active')
            ->when(!$user->isSuperAdmin(), function ($query) use ($user) {
                $query->where('id', currentCompany()?->id);
            })
            ->orderBy('name')
            ->paginate(5);

        return view('admin.companies.index', compact('companies'));
    }

    public function trash(): View
    {
        $this->authorize('viewAny', Company::class);

        $user = auth()->user();
        abort_unless($user->isSuperAdmin(), 403);

        $companies = Company::query()->where("status_company", "deleted")
            ->orderByDesc('updated_at')
            ->paginate(5);

        return view('admin.companies.trash', compact('companies'));
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
            'logo' => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('company_logos', 'public');
        }

        unset($validated['logo']);

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
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'logo' => 'nullable|image|max:4096',
            'remove_logo' => 'nullable|boolean',
        ]);

            $currentLogoPath = $company->getAttribute('logo_path');

            if ($request->boolean('remove_logo') && $currentLogoPath) {
                Storage::disk('public')->delete($currentLogoPath);
            $validated['logo_path'] = null;
        }

        if ($request->hasFile('logo')) {
            if ($currentLogoPath) {
                Storage::disk('public')->delete($currentLogoPath);
            }
            $validated['logo_path'] = $request->file('logo')->store('company_logos', 'public');
        }

        unset($validated['logo'], $validated['remove_logo']);

        $company->update($validated);

        $route = $request->user()->isSuperAdmin() ? 'admin.companies.show' : 'company.current';
        $parameters = $request->user()->isSuperAdmin() ? [$company] : [];

        return redirect()
            ->route($route, $parameters)
            ->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company): RedirectResponse
    {
        $this->authorize('delete', $company);

        DB::transaction(function () use ($company) {
            $company->posts()->where('status', '!=', 'trash')->update(['deleted_by' => auth()->id(), 'status' => 'trash']);
            DB::table('company_user')
                ->where('company_id', $company->id)
                ->where('status_membership', 'active')
                ->update(['status_membership' => 'deleted']);
            $company->update(['deleted_by' => auth()->id(), 'status_company' => 'deleted']);
        });

        return redirect()
            ->route('admin.companies.index')
            ->with('success', 'Company deleted successfully.');
    }

    public function restore(int $company): RedirectResponse
    {
        $model = Company::query()->where("status_company", "deleted")->findOrFail($company);
        $this->authorize('restore', $model);

        DB::transaction(function () use ($model) {
            $model->update(['deleted_by' => null, 'status_company' => 'active']);
            $model->posts()->where('status', 'trash')->update(['deleted_by' => null, 'status' => 'draft']);
            DB::table('company_user')
                ->where('company_id', $model->id)
                ->where('status_membership', 'deleted')
                ->update(['status_membership' => 'active']);
        });

        return redirect()
            ->route('admin.companies.trash')
            ->with('success', 'Company restored successfully.');
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
