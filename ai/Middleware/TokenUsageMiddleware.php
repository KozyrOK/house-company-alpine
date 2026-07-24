<?php

namespace App\Ai\Middleware;

use App\Models\ChatSetting;
use App\Models\ConversationMessage;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class TokenUsageMiddleware
{
    public function assertAllowed(User $user): void
    {
        $limit = ChatSetting::current()->daily_request_limit;
        $used = ConversationMessage::query()
            ->where('role', 'user')
            ->whereDate('created_at', now()->toDateString())
            ->whereHas('conversation', fn ($q) => $q->where('user_id', $user->id))
            ->count();

        if ($used >= $limit) {
            throw ValidationException::withMessages(['message' => __('app.chat.limit_exceeded')]);
        }
    }

    public function countTokens(string $text): int
    {
        return max(1, str_word_count(strip_tags($text)));
    }
}