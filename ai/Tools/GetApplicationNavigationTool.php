<?php

namespace App\Ai\Tools;

use App\Models\User;

class GetApplicationNavigationTool
{
    public function handle(User $user): array
    {
        $items = [
            ['label' => __('app.layouts.chat'), 'route' => route('chat')],
            ['label' => __('app.layouts.dashboard'), 'route' => route('dashboard')],
        ];

        if ($user->canAccessAdminPanel()) {
            $items[] = ['label' => __('app.layouts.admin'), 'route' => route('admin.index')];
        }

        if ($user->canAccessMainPanel()) {
            $items[] = ['label' => __('app.layouts.main'), 'route' => route('main.index')];
            $items[] = ['label' => __('app.layouts.company'), 'route' => route('main.companies.show')];
            $items[] = ['label' => __('app.tables.title'), 'route' => route('main.posts.index')];
            $items[] = ['label' => __('app.users.users'), 'route' => route('main.users.index')];
        }

        return $items;
    }
}