<?php

namespace App\Services\Chat;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

class ChatHistoryService
{
    public function conversations(User $user)
    {
        return Conversation::where('user_id', $user->id)->latest()->get();
    }

    public function get(User $user, Conversation $conversation): Conversation
    {
        if ($conversation->user_id !== $user->id) throw new AuthorizationException();
        return $conversation->load('messages');
    }
}