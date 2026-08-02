<?php

namespace App\Services\Chat;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class ChatDomainContextService
{
    public function currentUser(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => trim($user->first_name.' '.$user->second_name),
            'email' => $user->email,
            'status' => $user->status_account,
            'is_superadmin' => $user->isSuperAdmin(),
        ];
    }

    public function currentCompany(User $user): ?array
    {
        $company = currentCompany();

        if (!$company || Gate::forUser($user)->denies('view', $company)) {
            return null;
        }

        return $this->companyInformation($user, $company);
    }

    public function currentMembership(User $user): array
    {
        $company = currentCompany();

        if (!$company) {
            return [
                'company' => null,
                'role' => $user->isSuperAdmin() ? 'superadmin' : null,
                'status' => $user->isSuperAdmin() ? 'active' : null,
                'permissions' => $this->currentUserPermissions($user),
            ];
        }

        $membership = $user->companies()
            ->where('companies.id', $company->id)
            ->first()?->pivot;

        return [
            'company' => $this->companyInformation($user, $company),
            'role' => $membership?->role ?? $user->roleIn($company),
            'status' => $membership?->status_membership,
            'permissions' => $this->currentUserPermissions($user),
        ];
    }

    public function currentUserRoles(User $user): array
    {
        if ($user->isSuperAdmin()) {
            return [[
                'company' => null,
                'role' => 'superadmin',
                'status' => 'active',
            ]];
        }

        return $user->companies()
            ->wherePivotIn('status_membership', ['active', 'pending_admin'])
            ->orderBy('name')
            ->get()
            ->filter(fn (Company $company) => Gate::forUser($user)->allows('view', $company))
            ->map(fn (Company $company) => [
                'company' => ['id' => $company->id, 'name' => $company->name],
                'role' => $company->pivot->role,
                'status' => $company->pivot->status_membership,
            ])
            ->values()
            ->all();
    }

    public function currentUserPermissions(User $user): array
    {
        $company = currentCompany();

        return [
            'is_superadmin' => $user->isSuperAdmin(),
            'current_company' => $company ? ['id' => $company->id, 'name' => $company->name] : null,
            'role' => $company ? $user->roleIn($company) : ($user->isSuperAdmin() ? 'superadmin' : null),
            'can_view_companies' => Gate::forUser($user)->allows('viewAny', Company::class),
            'can_view_users' => Gate::forUser($user)->allows('viewAny', User::class),
            'can_access_admin_panel' => $user->canAccessAdminPanel(),
            'can_access_main_panel' => $user->canAccessMainPanel(),
            'can_manage_company_members' => $company ? Gate::forUser($user)->allows('create', [User::class, $company]) : false,
        ];
    }

    public function companyMembers(User $user, ?int $companyId = null, int $limit = 20): array
    {
        $company = $this->resolveCompany($user, $companyId);

        if (!$company || Gate::forUser($user)->denies('view', $company) || Gate::forUser($user)->denies('viewAny', User::class)) {
            return [];
        }

        return $company->users()
            ->limit($limit)
            ->get()
            ->filter(fn (User $member) => Gate::forUser($user)->allows('view', $member))
            ->map(fn (User $member) => [
                'id' => $member->id,
                'name' => trim($member->first_name.' '.$member->second_name),
                'email' => $member->email,
                'role' => $member->pivot->role,
                'status' => $member->pivot->status_membership,
            ])
            ->values()
            ->all();
    }

    public function companyInformation(User $user, Company|int|null $company = null): ?array
    {
        $company = $company instanceof Company ? $company : $this->resolveCompany($user, $company);

        if (!$company || Gate::forUser($user)->denies('view', $company)) {
            return null;
        }

        $company->loadCount(['users', 'posts']);

        return [
            'id' => $company->id,
            'name' => $company->name,
            'address' => $company->address,
            'city' => $company->city,
            'description' => $company->description,
            'status' => $company->status_company,
            'users_count' => $company->users_count,
            'posts_count' => $company->posts_count,
        ];
    }

    private function resolveCompany(User $user, ?int $companyId = null): ?Company
    {
        if (!$companyId) {
            return currentCompany();
        }

        return $user->isSuperAdmin()
            ? Company::find($companyId)
            : $user->companies()->where('companies.id', $companyId)->first();
    }
}