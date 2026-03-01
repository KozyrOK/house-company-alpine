<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

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
}
