<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static factory(int $int)
 */
class Company extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'address',
        'city',
        'logo_path',
        'description'
];
    public function getCompanyId(): int
    {
        return $this->id;
    }
    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'company_user')
            ->using(CompanyUser::class)
            ->withPivot('role')
            ->withTimestamps();
    }
    public function posts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function getLogoUrlAttribute(): string
    {
        return $this->logo_path
            ? asset('storage/' . $this->logo_path)
            : asset('images/default-image-company.jpg');
    }
}
