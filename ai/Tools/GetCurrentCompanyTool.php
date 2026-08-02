<?php

namespace App\Ai\Tools;

use App\Models\User;
use App\Services\Chat\ChatDomainContextService;

class GetCurrentCompanyTool
{
    public function __construct(private ChatDomainContextService $domain) {}

    public function name(): string
    {
        return 'getCurrentCompany';
    }

    public function description(): string
    {
        return 'Returns the company currently selected in the user session.';
    }

    public function handle(User $user): ?array
    {
        return $this->domain->currentCompany($user);
    }
}