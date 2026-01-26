<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CompanyController extends Controller
{

    public function index()
    {
        $this->authorize('viewAny', User::class);

        return view('admin.companies.index');
    }

    public function show(Company $company)
    {
        $this->authorize('view', $company);

        return view('admin.companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        $this->authorize('update', $company);

        return view('admin.companies.edit', compact('company'));
    }

    public function create()
    {
        $this->authorize('create', Company::class);

        return view('admin.companies.create');
    }

    public function store()
    {
        $this->authorize('create', Company::class);

        return redirect()->route('admin.companies.index');
    }

    public function update(Request $request, Company $company)
    {
        $this->authorize('update', $company);

        return redirect()->route('admin.companies.show', $company);
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
