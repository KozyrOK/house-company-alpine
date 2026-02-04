<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{

    public function logo(Request $request, ?int $companyId = null)
    {

        $user = $request->user();

        if ($user->isSuperAdmin()) {
            return response()->json([
                'logo_url' => asset('images/default-image-company.jpg'),
            ]);
        }

        if ($companyId) {
            $company = Company::find($companyId);

            if (!$company || !$user->belongsToCompany($companyId)) {
                return response()->json([
                    'logo_url' => asset('images/default-image-company.jpg'),
                ]);
            }

            return response()->json([
                'logo_url' => $company->logo_url,
            ]);
        }

        return response()->json([
            'logo_url' => asset('images/default-image-company.jpg'),
        ]);
    }

    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isSuperAdmin()) {
            return Company::paginate(10);
        }

        return $user->companies()->paginate(10);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $company = Company::create($validated);

        $superadmins = User::where('is_superadmin', true)->get();

        foreach ($superadmins as $sa) {
            $company->users()->attach($sa->id, ['role' => 'superadmin']);
        }

        return response()->json($company, 201);
    }

    public function show(Company $company)
    {
        return $company;
    }

    public function update(Request $request, Company $company)
    {
        $company->update($request->only('name'));
        return $company;
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return response()->noContent();
    }

    public function uploadLogo(Request $request, Company $company)
    {

        $validated = $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,webp,avif|max:5120',
        ]);

        $file = $request->file('logo');

        $filename = 'company_' . $company->id . '_' . time() . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs('company_logos', $filename, 'public');

        if ($company->logo_path && Storage::disk('public')->exists($company->logo_path)) {
            Storage::disk('public')->delete($company->logo_path);
        }

        $company->logo_path = $path;
        $company->save();

        return response()->json([
            'message' => 'Logo uploaded',
            'logo_url' => $company->logo_url,
            'company' => $company,
        ], 200);
    }

    public function deleteLogo(Request $request, Company $company)
    {

        if ($company->logo_path && Storage::disk('public')->exists($company->logo_path)) {
            Storage::disk('public')->delete($company->logo_path);
        }

        $company->logo_path = null;
        $company->save();

        return response()->json([
            'message' => 'Logo deleted',
            'logo_url' => $company->logo_url,
            'company' => $company,
        ], 200);
    }

}
