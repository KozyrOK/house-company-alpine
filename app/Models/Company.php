<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @method static factory(int $int)
 */
class Company extends Model
{
    use HasFactory;

    public mixed $logo_path;
    public mixed $logo_url;
    protected $fillable = [
        'name',
        'address',
        'city',
        'logo_path',
        'description',
        'status_company',
        'deleted_by'
];
    public function getCompanyId(): int
    {
        return $this->id;
    }
    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'company_user')
            ->using(CompanyUser::class)
            ->withPivot('role', 'status_membership')
            ->withTimestamps();
    }
    public function posts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function deleter(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function getLogoUrlAttribute(): string
    {
        if (!empty($this->attributes['logo_path']) && Storage::disk('public')->exists($this->attributes['logo_path'])) {
            return Storage::disk('public')->url($this->attributes['logo_path']);
        }
        return asset('images/default-image-company.webp');
    }
}
