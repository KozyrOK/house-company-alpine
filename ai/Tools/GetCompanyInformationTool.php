<?php

namespace App\Ai\Tools;

use App\Models\User;
use App\Services\Chat\ChatDomainContextService;

class GetCompanyInformationTool
{
    public function __construct(private ChatDomainContextService $domain) {}

    public function name(): string
    {
        return 'getCompanyInformation';
    }

    public function description(): string
    {
        return 'Returns authorized details about the current or requested company.';
    }

    public function handle(User $user, ?int $companyId = null): ?array
    {
        return $this->domain->companyInformation($user, $companyId);
    }
}