<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatSetting extends Model
{
    protected $fillable = ['provider', 'daily_request_limit'];

    protected $casts = ['daily_request_limit' => 'integer'];

    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'provider' => 'openai',
            'daily_request_limit' => 5,
        ]);
    }
}