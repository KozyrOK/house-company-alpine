<?php

namespace App\Services\Chat;

use App\Models\ChatSetting;

class ChatProviderConfigurationService
{
    public const PROVIDERS = ['openai', 'anthropic', 'gemini', 'ollama'];

    public function current(): array
    {
        $settings = ChatSetting::current();

        return [
            'provider' => $settings->provider,
            'daily_request_limit' => $settings->daily_request_limit,
            'is_configured' => $this->isConfigured($settings->provider),
        ];
    }

    public function isConfigured(string $provider): bool
    {
        return match ($provider) {
            'openai' => filled(config('services.openai.key')),
            'anthropic' => filled(config('services.anthropic.key')),
            'gemini' => filled(config('services.gemini.key')),
            'ollama' => filled(config('services.ollama.url')),
            default => false,
        };
    }
}