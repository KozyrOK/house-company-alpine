<?php

namespace App\Services\Chat;

use App\Ai\Agents\AdminAssistantAgent;
use App\Ai\Agents\SupportChatAgent;
use App\Ai\Middleware\TokenUsageMiddleware;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

class ChatService
{
    public function __construct(private SupportChatAgent $support, private AdminAssistantAgent $admin, private TokenUsageMiddleware $tokens) {}

    public function start(User $user, string $agent = 'support'): Conversation
    {
        if ($agent === 'admin' && !$user->isSuperAdmin()) throw new AuthorizationException();
        return Conversation::create(['user_id' => $user->id, 'agent' => $agent]);
    }

    public function send(User $user, Conversation $conversation, string $message): array
    {
        if ($conversation->user_id !== $user->id) throw new AuthorizationException();
        $this->tokens->assertAllowed($user);
        $conversation->messages()->create(['role' => 'user', 'content' => $message, 'input_tokens' => $this->tokens->countTokens($message)]);
        $response = ($conversation->agent === 'admin' ? $this->admin : $this->support)->respond($user, $message, $conversation);
        $assistant = $conversation->messages()->create(['role' => 'assistant', 'content' => $response['content'], 'structured_data' => $response['structured_data'], 'output_tokens' => $this->tokens->countTokens($response['content'])]);
        if (!$conversation->title) $conversation->update(['title' => (string) str($message)->limit(60)]);
        return ['message' => $assistant, 'conversation' => $conversation->fresh('messages')];
    }
}