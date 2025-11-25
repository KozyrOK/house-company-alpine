<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class CompanyController extends Controller
{

    public function index()
    {
        return view('admin.companies.index');
    }
    /**
     * Show a single company page.
     */
    public function show($companyId)
    {
        // Company data is fetched from API (/api/main/{id})
        return view('main.show', compact('companyId'));
    }
}

