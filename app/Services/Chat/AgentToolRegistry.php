<?php

namespace App\Services\Chat;

use App\Ai\Tools\GetApplicationNavigationTool;
use App\Ai\Tools\GetCompanyInformationTool;
use App\Ai\Tools\GetCompanyMembersTool;
use App\Ai\Tools\GetCurrentCompanyTool;
use App\Ai\Tools\GetCurrentMembershipTool;
use App\Ai\Tools\GetCurrentUserPermissionsTool;
use App\Ai\Tools\GetCurrentUserRolesTool;
use App\Ai\Tools\GetCurrentUserTool;
use App\Ai\Tools\GetUserCompaniesTool;
use App\Ai\Tools\SearchPostsTool;
use App\Ai\Tools\SearchUsersTool;
use App\Models\User;
use InvalidArgumentException;

class AgentToolRegistry
{
    public function __construct(
        private GetCurrentUserTool $currentUser,
        private GetCurrentCompanyTool $currentCompany,
        private GetCurrentMembershipTool $currentMembership,
        private GetCurrentUserRolesTool $currentUserRoles,
        private GetCurrentUserPermissionsTool $permissions,
        private GetUserCompaniesTool $companies,
        private GetCompanyInformationTool $companyInformation,
        private GetCompanyMembersTool $companyMembers,
        private SearchPostsTool $posts,
        private SearchUsersTool $users,
        private GetApplicationNavigationTool $navigation,
    ) {}

    public function descriptions(): array
    {
        return [
            'getCurrentUser' => $this->currentUser->description(),
            'getCurrentCompany' => $this->currentCompany->description(),
            'getCurrentMembership' => $this->currentMembership->description(),
            'getCurrentUserRoles' => $this->currentUserRoles->description(),
            'getCurrentUserPermissions' => 'Returns the authenticated user permissions in the current application context.',
            'getUserCompanies' => 'Lists companies available to the authenticated user.',
            'getCompanyInformation' => $this->companyInformation->description(),
            'getCompanyMembers' => $this->companyMembers->description(),
            'searchPosts' => 'Searches posts visible to the authenticated user. Optional arguments: term, status, limit.',
            'searchUsers' => 'Searches users visible to the authenticated user. Optional arguments: term, role, limit.',
            'getApplicationNavigation' => 'Lists application pages/routes available to the authenticated user.',
        ];
    }

    public function run(User $user, string $tool, array $arguments = []): mixed
    {
        return match ($tool) {
            'getCurrentUser' => $this->currentUser->handle($user),
            'getCurrentCompany' => $this->currentCompany->handle($user),
            'getCurrentMembership' => $this->currentMembership->handle($user),
            'getCurrentUserRoles' => $this->currentUserRoles->handle($user),
            'getCurrentUserPermissions' => $this->permissions->handle($user),
            'getUserCompanies' => $this->companies->handle($user),
            'getCompanyInformation' => $this->companyInformation->handle($user, $arguments['company_id'] ?? null),
            'getCompanyMembers' => $this->companyMembers->handle($user, $arguments['company_id'] ?? null, $arguments['limit'] ?? 20),
            'searchPosts' => $this->posts->handle($user, $arguments['term'] ?? null, $arguments['status'] ?? null, $arguments['limit'] ?? 10),
            'searchUsers' => $this->users->handle($user, $arguments['term'] ?? null, $arguments['role'] ?? null, $arguments['limit'] ?? 10),
            'getApplicationNavigation' => $this->navigation->handle($user),
            default => throw new InvalidArgumentException("Unknown AI tool [{$tool}]."),
        };
    }
}