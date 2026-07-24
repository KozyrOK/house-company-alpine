<?php

namespace App\Ai\StructuredOutputs;

class SearchUsersOutput { public function __construct(public string $intent = 'search_users', public ?string $role = null, public ?string $term = null) {} public function toArray(): array { return ['intent' => $this->intent, 'role' => $this->role, 'term' => $this->term]; } }