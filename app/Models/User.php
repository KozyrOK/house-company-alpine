<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Company;
use App\Models\CompanyUser;

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
        'phone'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    public function companies(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_user')
            ->using(CompanyUser::class)
            ->withPivot('role');
    }

    public function hasRole(array|string $roles, ?int $companyId = null): bool
    {
        $roles = (array) $roles;

        $query = $this->companies();

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        return $query->wherePivotIn('role', $roles)->exists();
    }

    public function belongsToCompany(int $companyId): bool
    {
        return $this->companies()->where('company_id', $companyId)->exists();
    }

}
