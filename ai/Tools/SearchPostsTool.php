<?php

namespace App\Ai\Tools;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class SearchPostsTool
{
    public function handle(User $user, ?string $term = null, ?string $status = null, int $limit = 10): array
    {
        if (Gate::forUser($user)->denies('viewAny', Post::class)) return [];

        $query = Post::with(['company:id,name', 'user:id,first_name,second_name'])->latest();
        if (!$user->isSuperAdmin() && currentCompany()) $query->where('company_id', currentCompany()->id);
        if ($status) $query->where('status', $status);
        if ($term) $query->where(fn ($q) => $q->where('title', 'like', "%{$term}%")->orWhere('content', 'like', "%{$term}%"));

        return $query->limit($limit)->get()->filter(fn (Post $post) => Gate::forUser($user)->allows('view', $post))->map(fn (Post $post) => [
            'id' => $post->id,
            'title' => $post->title,
            'status' => $post->status,
            'company' => $post->company?->name,
            'author' => trim(($post->user?->first_name ?? '').' '.($post->user?->second_name ?? '')),
            'created_at' => $post->created_at?->toDateTimeString(),
        ])->values()->all();
    }
}