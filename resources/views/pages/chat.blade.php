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

        <div x-cloak x-show="modalOpen" class="modal-open">
            <div class="flex h-[75vh] w-full max-w-3xl flex-col rounded-lg bg-white shadow-xl dark:bg-gray-900">
                <div class="flex items-center justify-between border-b p-4 dark:border-gray-700">
                    <h2 class="font-semibold">{{ __('app.chat.window') }}</h2>
                    <button type="button" class="text-2xl" x-on:click="modalOpen = false">×</button>
                </div>
                <div class="flex-1 space-y-3 overflow-y-auto p-4">
                    <template x-for="message in messages" :key="message.id ?? message.content">
                        <div class="flex" :class="message.role === 'user' ? 'justify-end' : 'justify-start'">
                            <div class="max-w-[75%] whitespace-pre-line rounded px-4 py-2" :class="message.role === 'user' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-gray-100'" x-text="message.content"></div>
                        </div>
                    </template>
                </div>
                <form class="flex gap-2 border-t p-4 dark:border-gray-700" x-on:submit.prevent="sendMessage()">
                    <input class="flex-1 rounded border px-3 py-2 dark:bg-gray-800" x-model="draft" placeholder="{{ __('app.chat.placeholder') }}">
                    <button class="rounded bg-blue-600 px-4 py-2 text-white disabled:opacity-50" :disabled="loading">{{ __('app.chat.send') }}</button>
                </form>
            </div>
        </div>

        <div x-cloak x-show="historyOpen" class="history-open">
            <h2 class="mb-3 font-semibold">{{ __('app.chat.history') }}</h2>
            <template x-for="item in conversations" :key="item.id">
                <button type="button" class="block w-full border-b py-2 text-left dark:border-gray-700" x-on:click="openConversation(item.id)" x-text="item.title || ('Conversation #' + item.id)"></button>
            </template>
        </div>

        <div x-cloak x-show="settingsOpen" class="settings-open">
            <h2 class="mb-3 font-semibold">{{ __('app.chat.settings') }}</h2>
            <label class="block">{{ __('app.chat.provider') }}
                <select class="mt-1 block rounded border p-2 dark:bg-gray-800" x-model="settings.provider">
                     <option value="ollama">Ollama</option><option value="openai">OpenAI</option><option value="anthropic">Anthropic</option><option value="gemini">Gemini</option>
                </select>
            </label>
            <label class="mt-3 block">{{ __('app.chat.daily_limit') }}
                <input type="number" min="1" class="mt-1 block rounded border p-2 dark:bg-gray-800" x-model.number="settings.daily_request_limit">
            </label>
            <p class="mt-2 text-sm" :class="settings.is_configured ? 'text-green-700 dark:text-green-300' : 'text-amber-700 dark:text-amber-300'" x-text="settings.is_configured ? '{{ __('app.chat.provider_configured') }}' : (settings.warnings?.join(' ') || '{{ __('app.chat.provider_not_configured') }}')"></p>
            <button type="button" class="mt-4 rounded bg-green-600 px-4 py-2 text-white" x-on:click="saveSettings()">{{ __('app.buttons.save') }}</button>
        </div>
    </section>
@endsection