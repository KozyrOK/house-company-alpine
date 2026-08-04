<?php

namespace App\Ai\Tools;

use App\Models\User;
use App\Services\Chat\ChatDomainContextService;

class GetCurrentUserTool
{
    public function __construct(private ChatDomainContextService $domain) {}

    public function name(): string
    {
        return 'getCurrentUser';
    }

    public function description(): string
    {
        return 'Returns the authenticated user profile and account status.';
    }

    public function handle(User $user): array
    {
        return $this->domain->currentUser($user);
    }

}