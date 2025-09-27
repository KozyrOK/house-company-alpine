<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Company::class);
        return Company::paginate();
    }

    public function store(Request $request)
    {
        $this->authorize('create', Company::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $company = Company::create($validated);

        return response()->json($company, 201);
    }

    public function show(int $companyId)
    {
        $company = Company::findOrFail($companyId);
        $this->authorize('view', $company->id);

        return $company;
    }

    public function update(Request $request, int $companyId)
    {
        $company = Company::findOrFail($companyId);
        $this->authorize('update', $company->id);

        $company->update($request->only('name'));

        return $company;
    }

    public function destroy(int $companyId)
    {
        $company = Company::findOrFail($companyId);
        $this->authorize('delete', $company->id);

        $company->delete();

        return response()->noContent();
    }
}

