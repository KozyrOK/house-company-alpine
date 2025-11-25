<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

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

        $superadmins = User::whereHas('companies', fn($q) => $q->where('role','superadmin'))->get();
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

}
