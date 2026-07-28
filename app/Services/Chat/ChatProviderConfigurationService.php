<?php

namespace App\Services\Chat;

use App\Models\ChatSetting;

class ChatProviderConfigurationService
{
    public const PROVIDERS = ['openai', 'anthropic', 'gemini', 'ollama'];

    public function current(): array
    {
        $settings = ChatSetting::current();
        $provider = $settings->provider;
        $warnings = $this->warnings($provider);

        return [
            'provider' => $provider,
            'daily_request_limit' => $settings->daily_request_limit,
            'is_configured' => $warnings === [],
            'warnings' => $warnings,
        ];
    }

    public function isConfigured(string $provider): bool
    {
        return $this->warnings($provider) === [];
    }

    public function warnings(string $provider): array
    {
        return match ($provider) {
            'openai' => filled(config('services.openai.key')) ? [] : ['OpenAI API key is missing. Set OPENAI_API_KEY or choose another provider.'],
            'anthropic' => filled(config('services.anthropic.key')) ? [] : ['Anthropic API key is missing. Set ANTHROPIC_API_KEY or choose another provider.'],
            'gemini' => filled(config('services.gemini.key')) ? [] : ['Gemini API key is missing. Set GEMINI_API_KEY or choose another provider.'],
            'ollama' => filled(config('services.ollama.url')) ? [] : ['Ollama URL is missing. Set OLLAMA_URL or choose another provider.'],
            default => ['Unsupported AI provider selected.'],
        };
    }
}