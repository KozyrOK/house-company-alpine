<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

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
}
