<?php

namespace App\Ai\Tools;

use App\Models\User;
use App\Services\Chat\ChatDomainContextService;

class GetCurrentUserRolesTool
{
    public function __construct(private ChatDomainContextService $domain) {}

    public function name(): string
    {
        return 'getCurrentUserRoles';
    }

    public function description(): string
    {
        return 'Returns all company roles assigned to the authenticated user.';
    }

    public function handle(User $user): array
    {
        return $this->domain->currentUserRoles($user);
    }
}