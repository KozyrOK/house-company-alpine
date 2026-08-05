@extends('_layouts.app')

@section('title', __('app.pages.chat'))

@section('content')
    <section x-data="chatComponent(@json(auth()->user()->isSuperAdmin()))" class="chat-wrapper">
        <div>
            <h1>{{ __('app.pages.chat') }}</h1>            
        </div>

        <div class="chat-button-wrapper">
            <button type="button" class="chat-button-start-chat" x-on:click="startChat(isSuperAdmin ? 'admin' : 'support')">{{ __('app.chat.start') }}</button>
            @if(auth()->user()->isSuperAdmin())
                <button type="button" class="chat-button-open-settings" x-on:click="openSettings()">{{ __('app.chat.settings') }}</button>
            @endif
            <button type="button" class="chat-button-load-history" x-on:click="loadHistory()">{{ __('app.chat.history') }}</button>
        </div>

        <div x-cloak x-show="modalOpen" class="chat-modal-overlay">
            <div class="chat-modal-window">
                <div class="chat-modal-header">
                    <h2 class="chat-panel-title">{{ __('app.chat.window') }}</h2>
                    <button type="button" class="chat-modal-close-button" x-on:click="modalOpen = false">×</button>
                </div>                
                <div class="chat-messages-list">
                    <template x-for="message in messages" :key="message.id ?? message.content">
                        <div class="chat-message-row" :class="message.role === 'user' ? 'chat-message-row-user' : 'chat-message-row-assistant'">
                            <div class="chat-message-bubble" :class="message.role === 'user' ? 'chat-message-bubble-user' : 'chat-message-bubble-assistant'" x-text="message.content"></div>
                        </div>
                    </template>
                </div>                
                <form class="chat-message-form" x-on:submit.prevent="sendMessage()">
                    <input class="chat-message-input" x-model="draft" placeholder="{{ __('app.chat.placeholder') }}">
                    <button class="chat-message-send-button" :disabled="loading">{{ __('app.chat.send') }}</button>
                </form>
            </div>
        </div>

        <div x-cloak x-show="historyOpen" class="chat-panel">
            <h2 class="chat-panel-title">{{ __('app.chat.history') }}</h2>
            <template x-for="item in conversations" :key="item.id">                
                <button type="button" class="chat-history-item" x-on:click="openConversation(item.id)" x-text="item.title || ('Conversation #' + item.id)"></button>
            </template>
        </div>
        
        <div x-cloak x-show="settingsOpen" class="chat-panel">
            <h2 class="chat-panel-title">{{ __('app.chat.settings') }}</h2>
            <label class="chat-field-label">{{ __('app.chat.provider') }}
                <select class="chat-field-control" x-model="settings.provider">
                    <option value="ollama">Ollama</option><option value="openai">OpenAI</option><option value="anthropic">Anthropic</option><option value="gemini">Gemini</option>
                </select>
            </label>  
            <label class="chat-field-label chat-field-label-spaced">{{ __('app.chat.daily_limit') }}
                <input type="number" min="1" class="chat-field-control" x-model.number="settings.daily_request_limit">
            </label>
            <p class="chat-settings-status" :class="settings.is_configured ? 'chat-settings-status-success' : 'chat-settings-status-warning'" x-text="settings.is_configured ? '{{ __('app.chat.provider_configured') }}' : (settings.warnings?.join(' ') || '{{ __('app.chat.provider_not_configured') }}')"></p>
            <button type="button" class="chat-settings-save-button" x-on:click="saveSettings()">{{ __('app.buttons.save') }}</button>
        </div>
    </section>
@endsection