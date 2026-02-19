<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Company;
use App\Models\CompanyUser;

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

    public function companies(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
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

    public function isSuperAdmin(?int $companyId = null): bool
    {
        if ($this->companies()->wherePivot('role', 'superadmin')->exists()) return true;

        if ($companyId !== null) {
            return $this->hasRole('superadmin', $companyId);
        }
        return false;
    }

    public function isAdminOrHigher(int $companyId): bool
    {
        return $this->hasRole(['admin', 'superadmin'], $companyId);
    }

    public function isAdminInAnyCompany(): bool
    {
        return $this->companies()->wherePivot('role', 'admin')->exists();
    }

    public function isСompanyHeadOrUser(): bool
    {
//      логика проверки User на предмет участия в компанииях в качестве обычного User (роль user или company_head)
//      Для отображения Main меню
        return false;
    }

    public function isOneCompanyUserOnly(): bool
    {
//      логика проверки User на предмет участия только в одной компании в качестве обычного User (роль user или company_head)
        return false;
    }

    public function adminCompanyIds(): \Illuminate\Support\Collection
    {
        return $this->companies()
            ->wherePivot('role', 'admin')
            ->pluck('companies.id');
    }

    public function isCompanyHeadOrHigher(int $companyId): bool
    {
        return $this->hasRole(['company_head', 'admin', 'superadmin'], $companyId);
    }

    public function belongsToCompany(int $companyId): bool
    {
        return $this->companies()->where('company_id', $companyId)->exists();
    }

    public function posts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Post::class);
    }

}
