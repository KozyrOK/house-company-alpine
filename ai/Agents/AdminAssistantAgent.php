<?php

namespace App\Ai\Agents;

use App\Models\ChatSetting;
use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\User;
use App\Services\Chat\ChatProviderConfigurationService;
use Illuminate\Auth\Access\AuthorizationException;

class AdminAssistantAgent
{
    public function __construct(private SupportChatAgent $support, private ChatProviderConfigurationService $providers) {}

    public function respond(User $user, string $message, ?Conversation $conversation = null): array
    {
        if (!$user->isSuperAdmin()) throw new AuthorizationException();

        $text = mb_strtolower($message);
        if (str_contains($text, 'setting') || str_contains($text, 'provider')) {
            $settings = ChatSetting::current();
            $provider = $this->providers->current();
            return ['content' => "AI provider: {$settings->provider}. Daily request limit: {$settings->daily_request_limit}. Provider configured: ".($provider['is_configured'] ? 'yes' : 'no').'.', 'structured_data' => ['intent' => 'chat_settings', 'settings' => $provider]];
        }

        if (str_contains($text, 'diagnostic')) {
            $data = [
                'conversations' => Conversation::count(),
                'messages' => ConversationMessage::count(),
                'provider' => $this->providers->current(),
            ];
            return ['content' => 'AI chat diagnostics: '.json_encode($data), 'structured_data' => ['intent' => 'diagnostics', 'diagnostics' => $data]];
        }
        
        return $this->support->respond($user, $message, $conversation);
    }
}