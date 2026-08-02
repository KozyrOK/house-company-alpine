<?php

namespace App\Ai\Tools;

use App\Models\User;
use App\Services\Chat\ChatDomainContextService;

class GetCompanyMembersTool
{
    public function __construct(private ChatDomainContextService $domain) {}

    public function name(): string
    {
        return 'getCompanyMembers';
    }

    public function description(): string
    {
        return 'Returns authorized members for the current or requested company.';
    }

    public function handle(User $user, ?int $companyId = null, int $limit = 20): array
    {
        return $this->domain->companyMembers($user, $companyId, $limit);
    }
}