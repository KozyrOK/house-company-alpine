<?php

namespace App\Ai\Agents;

use App\Ai\Tools\GetApplicationNavigationTool;
use App\Ai\Tools\GetCompanyDetailsTool;
use App\Ai\Tools\GetCurrentUserPermissionsTool;
use App\Ai\Tools\GetPostDetailsTool;
use App\Ai\Tools\GetUserCompaniesTool;
use App\Ai\Tools\SearchPostsTool;
use App\Ai\Tools\SearchUsersTool;
use App\Models\User;
use App\Services\Chat\ChatIntentService;

class SupportChatAgent
{
    public function __construct(
        private ChatIntentService $intents,
        private GetUserCompaniesTool $companies,
        private GetCompanyDetailsTool $companyDetails,
        private SearchPostsTool $posts,
        private GetPostDetailsTool $postDetails,
        private SearchUsersTool $users,
        private GetCurrentUserPermissionsTool $permissions,
        private GetApplicationNavigationTool $navigation,
    ) {}

    public function respond(User $user, string $message): array
    {
        $intent = $this->intents->classify($message);
        $id = $this->firstNumber($message);

        return match ($intent['intent']) {
            'list_companies' => $this->companies($user, $intent, $id),
            'search_posts' => $this->posts($user, $intent, $id),
            'search_users' => $this->users($user, $intent),
            'permissions' => $this->permissions($user, $intent),
            default => $this->navigation($user, $intent),
        };
    }

    private function companies(User $user, array $intent, ?int $id): array
    {
        $data = $id ? $this->companyDetails->handle($user, $id) : $this->companies->handle($user);
        return ['content' => $id ? $this->describeRecord('Company', $data) : $this->list('Companies available to you', $data, 'name'), 'structured_data' => $intent + ['items' => $data]];
    }

    private function posts(User $user, array $intent, ?int $id): array
    {
        $data = $id ? $this->postDetails->handle($user, $id) : $this->posts->handle($user, $intent['term'] ?? null, $intent['status'] ?? null);
        return ['content' => $id ? $this->describeRecord('Post', $data) : $this->list('Posts', $data, 'title'), 'structured_data' => $intent + ['items' => $data]];
    }

    private function users(User $user, array $intent): array
    {
        $data = $this->users->handle($user, $intent['term'] ?? null, $intent['role'] ?? null);
        return ['content' => $this->list('Company members', $data, 'name'), 'structured_data' => $intent + ['items' => $data]];
    }

    private function permissions(User $user, array $intent): array
    {
        $data = $this->permissions->handle($user);
        $content = 'Your current role is '.($data['role'] ?? 'not assigned').'. '
            .'Admin panel: '.($data['can_access_admin_panel'] ? 'yes' : 'no').'. '
            .'Main panel: '.($data['can_access_main_panel'] ? 'yes' : 'no').'.';
        return ['content' => $content, 'structured_data' => $intent + ['permissions' => $data]];
    }

    private function navigation(User $user, array $intent): array
    {
        $data = $this->navigation->handle($user);
        return ['content' => $this->list('Available navigation', $data, 'label'), 'structured_data' => $intent + ['items' => $data]];
    }

    private function list(string $title, array $items, string $field): string
    {
        if ($items === []) return $title.': no authorized records found.';
        return $title.":\n".collect($items)->map(fn ($item) => '- '.$item[$field])->implode("\n");
    }

    private function describeRecord(string $title, ?array $item): string
    {
        if (!$item) return $title.': no authorized record found.';
        return $title.":\n".collect($item)->map(fn ($value, $key) => $key.': '.(is_scalar($value) ? $value : json_encode($value)))->implode("\n");
    }

    private function firstNumber(string $message): ?int
    {
        preg_match('/\b\d+\b/', $message, $matches);
        return isset($matches[0]) ? (int) $matches[0] : null;
    }
}