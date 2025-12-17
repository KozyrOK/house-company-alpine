<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CompanyController extends Controller
{

    public function index()
    {
        return view('admin.companies.index');
    }

    public function show($companyId)
    {
        return view('admin.companies.show', compact('companyId'));
    }

    public function edit($companyId)
    {
        return view('admin.companies.edit', compact('companyId'));
    }

    public function create()
    {
        return view('admin.companies.create');
    }

    public function store()
    {

    }

    public function update(Request $request, Company $company)
    {

    }

    public function logo(Company $company): BinaryFileResponse
    {
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
