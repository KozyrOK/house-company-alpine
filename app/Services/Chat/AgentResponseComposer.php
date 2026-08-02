<?php

namespace App\Services\Chat;

use App\Models\Conversation;
use Throwable;

class AgentResponseComposer
{
    public function __construct(private AgentToolRegistry $tools, private ChatProviderConfigurationService $providers) {}

    public function compose(?Conversation $conversation, string $message, array $toolResults): string
    {
        $provider = $this->providers->current();

        if ($provider['is_configured']) {
            try {
                $answer = $this->composeWithModel($conversation, $message, $toolResults, $provider);
                if (filled($answer)) {
                    return $answer;
                }
            } catch (Throwable) {
                // Fall back to a deterministic response when the configured provider is unavailable.
            }
        }

        return $this->composeFallback($toolResults);
    }

    private function composeWithModel(?Conversation $conversation, string $message, array $toolResults, array $provider): string
    {
        if (!function_exists('agent')) {
            return '';
        }

        $history = $conversation?->messages()
            ->latest()
            ->limit(8)
            ->get()
            ->reverse()
            ->map(fn ($item) => strtoupper($item->role).': '.$item->content)
            ->implode("\n") ?? '';

        $prompt = "You are a helpful AI assistant for a Housing Company Laravel application.\n"
            ."Answer naturally and contextually using only the supplied tool results.\n"
            ."Do not invent application data. If data is missing, explain what is missing and ask one concise clarification.\n"
            ."When explaining roles, mention practical capabilities using the permissions payload.\n"
            ."Conversation history:\n{$history}\n"
            ."Latest user message: {$message}\n"
            ."Tool results:\n".json_encode($toolResults, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        return trim((string) agent()->prompt($prompt, provider: $provider['provider'], model: config('services.ai.model', env('AI_MODEL'))));
    }

    private function composeFallback(array $toolResults): string
    {
        $first = $toolResults[0] ?? null;
        $tool = $first['tool'] ?? null;
        $data = $first['result'] ?? null;

        return match ($tool) {
            'getCurrentMembership' => $this->membershipAnswer(is_array($data) ? $data : []),
            'getCurrentCompany' => $this->companyAnswer(is_array($data) ? $data : null),
            'getUserCompanies' => $this->listAnswer('Companies available to you', is_array($data) ? $data : [], 'name'),
            'getCompanyInformation' => $this->companyAnswer(is_array($data) ? $data : null),
            'getCompanyMembers' => $this->listAnswer('Company members', is_array($data) ? $data : [], 'name'),
            'searchPosts' => $this->listAnswer('Posts', is_array($data) ? $data : [], 'title'),
            'getApplicationNavigation' => $this->listAnswer('Available navigation', is_array($data) ? $data : [], 'label'),
            default => __('app.chat.default_answer'),
        };
    }

    private function membershipAnswer(array $membership): string
    {
        $company = $membership['company']['name'] ?? null;
        $role = $membership['role'] ?? null;
        $permissions = $membership['permissions'] ?? [];

        if (!$role) {
            return 'I could not find an active role for you in the current company context.';
        }

        $roleLabel = str($role)->replace('_', ' ')->title();
        $where = $company ? " in {$company}" : '';
        $capabilities = [];

        if ($permissions['can_manage_company_members'] ?? false) {
            $capabilities[] = 'manage company members';
        }

        if ($permissions['can_access_admin_panel'] ?? false) {
            $capabilities[] = 'access the admin panel';
        }

        if ($permissions['can_access_main_panel'] ?? false) {
            $capabilities[] = 'use the main company workspace';
        }

        $suffix = $capabilities === [] ? '' : ' This role allows you to '.implode(', ', $capabilities).'.';

        return "Your role{$where} is {$roleLabel}.{$suffix}";
    }

    private function companyAnswer(?array $company): string
    {
        if (!$company) {
            return 'I could not find a current company in your session.';
        }

        return "Current company: {$company['name']} ({$company['city']}). It has {$company['users_count']} members and {$company['posts_count']} posts.";
    }

    private function listAnswer(string $title, array $items, string $field): string
    {
        if ($items === []) {
            return $title.': no authorized records found.';
        }

        return $title.":\n".collect($items)->map(fn ($item) => '- '.$item[$field])->implode("\n");
    }
}