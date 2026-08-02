<?php

namespace App\Ai\Agents;

use App\Models\Conversation;
use App\Models\User;
use App\Services\Chat\AgentResponseComposer;
use App\Services\Chat\AgentToolPlanner;
use App\Services\Chat\AgentToolRegistry;

class SupportChatAgent
{
    public function __construct(
        private AgentToolPlanner $planner,
        private AgentToolRegistry $tools,
        private AgentResponseComposer $composer,
    ) {}
    
    public function instructions(): string
    {
        return 'You are a Housing Company AI assistant. Understand the user semantically, use the available application tools before answering about private application data, and answer naturally from tool results only.';
    }

    public function tools(): array
    {        
        return $this->tools->descriptions();
    }

    public function respond(User $user, string $message, ?Conversation $conversation = null): array
    {        
        $plan = $this->planner->plan($user, $conversation, $message);
        $toolResults = collect($plan)
            ->map(fn (array $step) => [
                'tool' => $step['name'],
                'arguments' => $step['arguments'] ?? [],
                'result' => $this->tools->run($user, $step['name'], $step['arguments'] ?? []),
            ])
            ->values()
            ->all();
    
        return [
            'content' => $this->composer->compose($conversation, $message, $toolResults),
            'structured_data' => [
                'tool_plan' => $plan,
                'tool_results' => $toolResults,
            ],
        ];
    }
}