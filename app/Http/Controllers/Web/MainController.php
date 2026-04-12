<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class MainController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        if (!$user->canAccessMainPanel()) {
            abort(403);
        }

        $companies = $user->companies()
            ->orderBy('name')
            ->get();

        return view('pages.main', [
            'companies' => $companies,
            'showCompaniesCard' => $companies->count() > 1,
            'showUsersCard' => $companies->isNotEmpty(),
            'showPostsCard' => $companies->isNotEmpty(),
        ]);
    }
}
