<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class MainController extends Controller
{
    public function index(): View
    {
        $company = currentCompany();

        if (!auth()->user()->canAccessMainPanel() || !$company) {
            abort(403);
        }

        return view('pages.main', [
            'company' => $company,
            'showUsersCard' => true,
            'showPostsCard' => true,
        ]);
    }
}
