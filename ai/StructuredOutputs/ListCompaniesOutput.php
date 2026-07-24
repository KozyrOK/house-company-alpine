<?php

namespace App\Ai\StructuredOutputs;

class ListCompaniesOutput { public function __construct(public string $intent = 'list_companies') {} public function toArray(): array { return ['intent' => $this->intent]; } }