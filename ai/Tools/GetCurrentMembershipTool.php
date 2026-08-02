<?php

namespace App\Ai\Tools;

use App\Models\User;
use App\Services\Chat\ChatDomainContextService;

class GetCurrentMembershipTool
{
    public function __construct(private ChatDomainContextService $domain) {}

    public function name(): string
    {
        return 'getCurrentMembership';
    }

    public function description(): string
    {
        return 'Returns the authenticated user membership, role, status, current company, and permissions for the selected company.';
    }

    public function handle(User $user): array
    {
        return $this->domain->currentMembership($user);
    }
}