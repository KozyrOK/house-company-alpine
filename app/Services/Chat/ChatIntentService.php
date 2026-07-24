<?php

namespace App\Services\Chat;

use App\Ai\StructuredOutputs\ListCompaniesOutput;
use App\Ai\StructuredOutputs\SearchPostsOutput;
use App\Ai\StructuredOutputs\SearchUsersOutput;

class ChatIntentService
{
    public function classify(string $message): array
    {
        $text = mb_strtolower($message);

        if (str_contains($text, 'compan')) {
            return (new ListCompaniesOutput())->toArray();
        }

        if (str_contains($text, 'post') || str_contains($text, 'latest') || str_contains($text, 'recent')) {
            return (new SearchPostsOutput(status: str_contains($text, 'active') ? 'active' : null))->toArray();
        }

        if (str_contains($text, 'admin') || str_contains($text, 'user') || str_contains($text, 'member')) {
            return (new SearchUsersOutput(role: str_contains($text, 'admin') ? 'admin' : null))->toArray();
        }

        if (str_contains($text, 'permission') || str_contains($text, 'role') || str_contains($text, 'can i')) {
            return ['intent' => 'permissions'];
        }

        return ['intent' => 'navigation_help'];
    }
}