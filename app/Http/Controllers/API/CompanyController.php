<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyUser;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Database\Eloquent\Collection
    {
        $this->authorize('viewAny', Company::class);

        return Company::all();
    }

    /**
     * Store a newly created resource in storage.
     * */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->authorize('create', Company::class);

        $company = Company::create($request->validate([
            'name' => 'required|string|max:255',
        ]));

        CompanyUser::create([
            'user_id' => $request->user()->id,
            'company_id' => $company->id,
            'role' => 'superadmin',
        ]);

        return response()->json($company, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company): Company
    {
        $this->authorize('view', $company);

        return $company;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company): Company
    {
        $this->authorize('update', $company);

        $company->update($request->validate([
            'name' => 'required|string|max:255',
        ]));
        return $company;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company): \Illuminate\Http\JsonResponse
    {
        $this->authorize('delete', $company);

        $company->delete();

        return response()->json(null, 204);
    }
}
