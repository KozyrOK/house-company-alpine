<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ChatSetting;
use App\Services\Chat\ChatProviderConfigurationService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ChatSettingsController extends Controller
{
    public function __construct(private ChatProviderConfigurationService $providers) {}

    public function show(Request $request)
    {
        abort_unless($request->user()->isSuperAdmin(), 403);

        return response()->json($this->providers->current());
    }

    public function update(Request $request)
    {
        abort_unless($request->user()->isSuperAdmin(), 403);

        $data = $request->validate([
            'provider' => ['required', Rule::in(ChatProviderConfigurationService::PROVIDERS)],
            'daily_request_limit' => ['required', 'integer', 'min:1', 'max:1000'],
        ]);

        $settings = ChatSetting::current();
        $settings->update($data);

        return response()->json($this->providers->current());
    }
}