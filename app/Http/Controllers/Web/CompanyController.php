<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Handles frontend (Blade) company pages.
 * Fetching and editing company data is done via API controllers.
 */
class CompanyController extends Controller
{
    /**
     * Show a list of companies that belong to the current user.
     */
    public function index()
    {
        // Blade view will load companies from /api/companies using Alpine.js or fetch()
        return view('companies.index');
    }

    /**
     * Show a single company page.
     */
    public function show($companyId)
    {
        // Company data is fetched from API (/api/companies/{id})
        return view('companies.show', compact('companyId'));
    }
}

