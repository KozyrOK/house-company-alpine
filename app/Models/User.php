<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

/**
 * @method static create(array $array)
 * @method static where(string $string, mixed $email)
 * @mixin IdeHelperUser
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'second_name',
        'email',
        'password',
        'google_id',
        'facebook_id',
        'x_id',
        'image_path',
        'phone',
        'status_account',
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relationships
     */

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Core Role Logic
     */
    public function hasRole(array|string $roles, int $companyId): bool
    {
        $roles = (array) $roles;

        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->companies()
            ->where('company_id', $companyId)
            ->wherePivotIn('role', $roles)
            ->exists();
    }

    public function belongsToCompany(int $companyId): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->companies()
            ->where('company_id', $companyId)
            ->exists();
    }

    public function isSuperAdmin(): bool
    {
        return $this->companies()
            ->wherePivot('role', 'superadmin')
            ->exists();
    }

    public function isAdminOrHigher(int $companyId): bool
    {
        return $this->hasRole('admin', $companyId);
    }

    public function isCompanyHeadOrHigher(int $companyId): bool
    {
        return $this->hasRole(['admin', 'company_head'], $companyId);
    }

    public function isUserInCompany(int $companyId): bool
    {
        return $this->hasRole(['user'], $companyId);
    }

    public function isAdminInAnyCompany(): bool
    {
        return $this->companies()
            ->wherePivotIn('role', ['admin', 'superadmin'])
            ->exists();
    }

    public function adminCompanyIds(): array
    {
        if ($this->isSuperAdmin()) {
            return Company::pluck('id')->all();
        }

        return $this->companies()
            ->wherePivot('role', 'admin')
            ->pluck('companies.id')
            ->all();
    }

    public function companyHeadCompanyIds(): array
    {
        if ($this->isSuperAdmin()) {
            return Company::pluck('id')->all();
        }

        return $this->companies()
            ->wherePivotIn('role', ['admin', 'company_head'])
            ->pluck('companies.id')
            ->all();
    }

    public function canAccessAdminPanel(): bool
    {
        return $this->isSuperAdmin() || $this->isAdminInAnyCompany();
    }

    public function canAccessMainPanel(): bool
    {
        if ($this->isSuperAdmin()) {
            return false;
        }

        return $this->companies()
            ->wherePivotIn('role', ['user', 'company_head', 'admin'])
            ->exists();
    }

    public function roleInCompany(Company $company): ?string
    {
        return $this->companies()
            ->where('company_id', $company->id)
            ->first()?->pivot->role;
    }

}
