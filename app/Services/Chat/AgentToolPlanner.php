<?php

namespace App\Services\Chat;

use App\Models\Conversation;
use App\Models\User;
use Throwable;

class AgentToolPlanner
{
    public function __construct(private AgentToolRegistry $tools, private ChatProviderConfigurationService $providers) {}

    public function plan(User $user, ?Conversation $conversation, string $message): array
    {
        $provider = $this->providers->current();

        if ($provider['is_configured']) {
            try {
                $planned = $this->planWithModel($conversation, $message, $provider);
                if ($planned !== []) {
                    return $planned;
                }
            } catch (Throwable) {
                // Fall back to deterministic planning when the configured provider is unavailable.
            }
        }

        return $this->fallbackPlan($message);
    }

    private function planWithModel(?Conversation $conversation, string $message, array $provider): array
    {
        if (!function_exists('agent')) {
            return [];
        }

        $response = agent()->prompt($this->planningPrompt($conversation, $message), provider: $provider['provider'], model: config('services.ai.model', env('AI_MODEL')));
        $json = $this->extractJson((string) $response);

        return $this->normalizePlan($json ? json_decode($json, true) : null);
    }

    private function planningPrompt(?Conversation $conversation, string $message): string
    {
        $history = $conversation?->messages()
            ->latest()
            ->limit(8)
            ->get()
            ->reverse()
            ->map(fn ($item) => strtoupper($item->role).': '.$item->content)
            ->implode("\n") ?? '';

        return "You are the tool planner for a Housing Company AI assistant.\n"
            ."Choose the minimal application tools needed to answer the user's latest message semantically.\n"
            ."Do not rely on exact keywords. Resolve pronouns and follow-up questions from conversation history.\n"
            ."If a user asks about their role, membership, permissions, capabilities, or whether they are an admin, call getCurrentMembership.\n"
            ."If current company context may be needed, call getCurrentCompany before broader company queries.\n"
            ."Available tools and descriptions:\n".json_encode($this->tools->descriptions(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."\n"
            ."Return only valid JSON in this shape: {\"tools\":[{\"name\":\"toolName\",\"arguments\":{}}]}.\n"
            ."Conversation history:\n{$history}\n"
            ."Latest user message: {$message}";
    }

    private function extractJson(string $response): ?string
    {
        if (preg_match('/\{.*\}/s', $response, $matches)) {
            return $matches[0];
        }

        return null;
    }

    private function normalizePlan(mixed $plan): array
    {
        if (!is_array($plan) || !isset($plan['tools']) || !is_array($plan['tools'])) {
            return [];
        }

        return collect($plan['tools'])
            ->filter(fn ($tool) => is_array($tool) && isset($tool['name']))
            ->map(fn ($tool) => [
                'name' => (string) $tool['name'],
                'arguments' => is_array($tool['arguments'] ?? null) ? $tool['arguments'] : [],
            ])
            ->values()
            ->all();
    }

    private function fallbackPlan(string $message): array
    {
        $text = mb_strtolower($message);

        if (preg_match('/\b(role|permission|admin|membership|can i|what can i do|роль|права|админ|адмін|могу|можу)\b/u', $text)) {
            return [['name' => 'getCurrentMembership', 'arguments' => []]];
        }

        if (preg_match('/\b(company|companies|компан|компані)\b/u', $text)) {
            return [['name' => 'getUserCompanies', 'arguments' => []]];
        }

        if (preg_match('/\b(member|user|users|пользовател|користувач|участник|учасник)\b/u', $text)) {
            return [['name' => 'getCompanyMembers', 'arguments' => []]];
        }

        if (preg_match('/\b(post|posts|latest|recent|пост|новост|оголош|публикац|публікац)\b/u', $text)) {
            return [['name' => 'searchPosts', 'arguments' => []]];
        }

        if (preg_match('/\b(navigation|navigate|menu|page|where|навигац|навігац|меню|страниц|сторін)\b/u', $text)) {
            return [['name' => 'getApplicationNavigation', 'arguments' => []]];
        }

        return [
            ['name' => 'getCurrentMembership', 'arguments' => []],
            ['name' => 'getCurrentCompany', 'arguments' => []],
        ];
    }
}