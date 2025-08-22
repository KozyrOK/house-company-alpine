<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'first_name', 'second_name', 'email', 'password', 'google_id', 'facebook_id', 'x_id', 'image_path', 'company_id', 'phone',
    ];

    protected $hidden = [
        'password'
    ];

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
