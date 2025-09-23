<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property string $role
 */
class CompanyUser extends Pivot
{

    protected $table = 'company_user';

    protected $fillable = [
        'user_id',
        'company_id',
        'role'
    ];

    public static function hasRole(int $userId, int $companyId, array|string $roles): bool
    {
        $roles = (array) $roles;

        return self::where('user_id', $userId)
            ->where('company_id', $companyId)
            ->whereIn('role', $roles)
            ->exists();
    }

}
