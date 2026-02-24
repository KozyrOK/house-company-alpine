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

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_user')
            ->using(CompanyUser::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function hasRole(array|string $roles, int $companyId): bool
    {
        $roles = (array) $roles;

        if (in_array('superadmin', $roles) &&
            $this->companies()->wherePivot('role', 'superadmin')->exists()) {
            return true;
        }

        return $this->companies()
            ->where('company_id', $companyId)
            ->wherePivotIn('role', $roles)
            ->exists();
    }

    public function belongsToCompany(int $companyId): bool
    {
        return $this->companies()->where('company_id', $companyId)->exists();
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function isSuperAdmin(?int $companyId = null): bool
    {
        return $this->companies()->wherePivot('role', 'superadmin')->exists();
    }

    public function isAdminOrHigher(int $companyId): bool
    {
        return $this->hasRole(['admin', 'superadmin'], $companyId);
    }

    public function isAdminInAnyCompany(): bool
    {
        return $this->companies()->wherePivot('role', 'admin')->exists();
    }

    public function isCompanyHeadOrUser(): bool
    {
        return $this->companies()
                    ->wherePivotIn('role', ['company_head', 'user'])
                    ->exists();
    }

    public function isOneCompanyUserOnly(): bool
    {
        return $this->companies()
                    ->wherePivotIn('role', ['company_head', 'user'])
                    ->count() === 1
            && !$this->isAdminInAnyCompany()
            && !$this->isSuperAdmin();
    }

    public function adminCompanyIds(): Collection
    {
        return $this->companies()
            ->wherePivot('role', 'admin')
            ->pluck('companies.id');
    }

    public function isCompanyHeadOrHigher(int $companyId): bool
    {
        return $this->hasRole(['company_head', 'admin', 'superadmin'], $companyId);
    }

}
