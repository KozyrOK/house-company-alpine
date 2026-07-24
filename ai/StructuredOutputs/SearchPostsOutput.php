<?php

namespace App\Ai\StructuredOutputs;

class SearchPostsOutput { public function __construct(public string $intent = 'search_posts', public ?string $status = null, public ?string $term = null) {} public function toArray(): array { return ['intent' => $this->intent, 'status' => $this->status, 'term' => $this->term]; } }