<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CompanyController extends Controller
{

    public function index(Request $request): View
    {
        if ($request->routeIs('main.index')) {
            $user = $request->user();

            $companiesQuery = $user->isSuperAdmin()
                ? Company::query()
                : $user->companies();

            $companies = $companiesQuery
                ->withCount(['users', 'posts'])
                ->orderBy('name')
                ->get();

            if (!$user->isSuperAdmin() && $companies->count() === 1) {
                $company = $companies->first();

                if (!$user->isAdminOrHigher($company->id)) {
                    return redirect()->route('main.posts.index', $company);
                }
            }

            $companyIds = $companies->pluck('id');

            $usersQuery = User::query()
                ->with('companies:id,name')
                ->orderBy('first_name');

            $postsQuery = Post::query()
                ->with(['company:id,name', 'user:id,first_name,second_name'])
                ->latest();

            if (!$user->isSuperAdmin()) {
                $usersQuery->whereHas('companies', fn ($query) => $query->whereIn('companies.id', $companyIds));
                $postsQuery->whereIn('company_id', $companyIds);
            }

            $users = $usersQuery->get();
            $posts = $postsQuery->take(10)->get();

            return view('pages.main', compact('companies', 'users', 'posts'));
        }

        $this->authorize('viewAny', Company::class);

        $query = Company::query()->orderBy('name');

        if (!$request->user()->isSuperAdmin()) {
            $adminCompanyIds = $request->user()->adminCompanyIds();
            $query->whereIn('id', $adminCompanyIds);
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->string('city') . '%');
        }

        $companies = $query->paginate(15);

        return view('admin.companies.index', compact('companies'));
    }

    public function show(Request $request, Company $company): View
    {
        $this->authorize('view', $company);

        if ($request->routeIs('main.show')) {
            $company->load([
                'users' => fn ($query) => $query->orderBy('first_name'),
                'posts' => fn ($query) => $query->latest()->with('user:id,first_name,second_name'),
            ]);

            return view('user.companies.show', compact('company'));
        }

        if (!$request->user()->isAdminOrHigher($company->id)) {
            abort(403);
        }

        $company->loadCount(['users', 'posts']);

        return view('admin.companies.show', compact('company'));
    }

    public function create(): View
    {
        $this->authorize('create', Company::class);

        return view('admin.companies.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Company::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
        ]);

        $company = Company::create($validated);

        $company->users()->syncWithoutDetaching([
            $request->user()->id => ['role' => 'superadmin'],
        ]);

        return redirect()->route('admin.companies.show', $company)
            ->with('status', 'Company created successfully');
    }

    public function edit(Company $company): View
    {
        $this->authorize('update', $company);

        return view('admin.companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company): RedirectResponse
    {
        $this->authorize('update', $company);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|nullable|string|max:255',
            'city' => 'sometimes|nullable|string|max:255',
            'description' => 'sometimes|nullable|string|max:2000',
        ]);

        $company->update($validated);

        return redirect()->route('admin.companies.show', $company)
            ->with('status', 'Company updated successfully');
    }

    public function destroy(Company $company): RedirectResponse
    {
        $this->authorize('delete', $company);

        $company->delete();

        return redirect()->route('admin.companies.index')
            ->with('status', 'Company deleted successfully');
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
