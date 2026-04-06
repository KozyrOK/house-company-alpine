<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminCompanyController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Company::class);

        return Company::orderBy('name')->paginate();
    }

    public function show(Company $company)
    {
        $this->authorize('view', $company);

        return $company->loadCount(['users','posts']);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Company::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        return Company::create($validated);
    }

    public function update(Request $request, Company $company)
    {
        $this->authorize('update', $company);

        $company->update($request->validate([
            'name' => 'sometimes|required|string|max:255',
        ]));

        return $company;
    }

    public function destroy(Company $company)
    {
        $this->authorize('delete', $company);

        $company->delete();

        return response()->noContent();
    }

    public function uploadLogo(Request $request, Company $company)
    {
        $this->authorize('update', $company);

        $validated = $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,webp,avif|max:5120',
        ]);

        $file = $validated['logo'];
        $filename = 'company_' . $company->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('company_logos', $filename, 'public');

        if ($company->logo_path && Storage::disk('public')->exists($company->logo_path)) {
            Storage::disk('public')->delete($company->logo_path);
        }

        $company->update(['logo_path' => $path]);

        return response()->json([
            'message' => 'Logo uploaded',
            'logo_url' => $company->fresh()->logo_url,
            'company' => $company->fresh(),
        ]);

    }

    public function deleteLogo(Company $company)
    {
        $this->authorize('update', $company);

        if ($company->logo_path && Storage::disk('public')->exists($company->logo_path)) {
            Storage::disk('public')->delete($company->logo_path);
        }

        $company->update(['logo_path' => null]);

        return response()->json([
            'message' => 'Logo deleted',
            'logo_url' => $company->fresh()->logo_url,
            'company' => $company->fresh(),
        ]);
    }
}
