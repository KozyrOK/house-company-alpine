<?php

namespace App\Ai\Tools;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class GetPostDetailsTool
{
    public function handle(User $user, int $postId): ?array
    {
        $post = Post::with(['company:id,name', 'user:id,first_name,second_name'])->find($postId);
        if (!$post || Gate::forUser($user)->denies('view', $post)) return null;

        return [
            'id' => $post->id,
            'title' => $post->title,
            'content' => $post->content,
            'status' => $post->status,
            'company' => $post->company?->name,
            'author' => trim(($post->user?->first_name ?? '').' '.($post->user?->second_name ?? '')),
        ];
    }
}