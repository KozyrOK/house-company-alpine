<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property string $role
 */
class CompanyUser extends Pivot
{

    protected $table = 'company_user';

    protected $fillable = ['user_id', 'company_id', 'role'];

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isHead(): bool
    {
        return $this->role === 'company_head';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }
}
