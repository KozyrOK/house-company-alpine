<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MainController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $user = auth()->user();

        if (!$user->canAccessMainPanel()) {
            abort(403);
        }

        $companies = $user->companies()
            ->orderBy('name')
            ->get();

        if ($companies->count() === 1) {
            $company = $companies->first();
            $role = $user->roleInCompany($company);

            if ($role === 'user') {
                return redirect()->route('main.posts.index', $company);
            }
        }

        return view('pages.main', [
            'companies' => $companies,
            'showCompaniesCard' => $companies->count() > 1,
            'showUsersCard' => $companies->isNotEmpty(),
            'showPostsCard' => $companies->isNotEmpty(),
        ]);
    }
}
