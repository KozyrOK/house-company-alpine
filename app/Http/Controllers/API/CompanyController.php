<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{

    public function logo(Request $request, ?Company $company = null): JsonResponse
    {

        $user = $request->user();

        if ($company && ($user->isSuperAdmin() || $user->belongsToCompany($company->id))) {
            return response()->json(['logo_url' => $company->logo_url]);
        }

        return response()->json([
            'logo_url' => asset('images/default-image-company.jpg'),
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $isAdminRoute = $request->is('api/admin/*');

        $companies = $user->isSuperAdmin()
            ? Company::query()->orderBy('name')->paginate(15)
            : ($isAdminRoute
                ? Company::query()->whereIn('id', $user->adminCompanyIds())->orderBy('name')->paginate(15)
                : $user->companies()->orderBy('name')->paginate(15));
        return response()->json($companies);
    }

    public function store(Request $request): JsonResponse
    {
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

        return response()->json($company, 201);
    }

    public function show(Company $company): JsonResponse
    {
        if (request()->is('api/admin/*') && !request()->user()->isAdminOrHigher($company->id)) {
            abort(403);
        }
        return response()->json($company->loadCount(['users', 'posts']));
    }

    public function update(Request $request, Company $company): JsonResponse
    {
        if (request()->is('api/admin/*') && !request()->user()->isAdminOrHigher($company->id)) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|nullable|string|max:255',
            'city' => 'sometimes|nullable|string|max:255',
            'description' => 'sometimes|nullable|string|max:2000',
        ]);

        $company->update($validated);

        return response()->json($company->fresh());
    }

    public function destroy(Company $company): JsonResponse
    {
        if (request()->is('api/admin/*') && !request()->user()->isAdminOrHigher($company->id)) {
            abort(403);
        }
        $company->delete();
        return response()->json([], 204);
    }

    public function uploadLogo(Request $request, Company $company): JsonResponse
    {
        if (request()->is('api/admin/*') && !request()->user()->isAdminOrHigher($company->id)) {
            abort(403);
        }

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

    public function deleteLogo(Company $company): JsonResponse
    {
        if (request()->is('api/admin/*') && !request()->user()->isAdminOrHigher($company->id)) {
            abort(403);
        }

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
