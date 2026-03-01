<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Models\Post;
use Illuminate\View\View;

class MainController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        return view('pages.main', [
            'canViewCompanies' => $user->can('viewAny', Company::class),
            'canViewUsers'     => $user->can('viewAny', User::class),
            'canViewPosts'     => $user->can('viewAny', Post::class),
        ]);
    }
}
