<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * @method static factory(int $int)
 */
class Company extends Model
{
    use HasFactory, SoftDeletes;

    public mixed $logo_path;
    protected $fillable = [
        'name',
        'address',
        'city',
        'logo_path',
        'description',
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
            ->withPivot('role')
            ->withTimestamps()
            ->withTrashed();
    }
    public function posts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function deleter(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by')->withTrashed();
    }

    public function getLogoUrlAttribute(): string
    {
        if (!empty($this->attributes['logo_path']) && Storage::disk('public')->exists($this->attributes['logo_path'])) {
            return Storage::disk('public')->url($this->attributes['logo_path']);
        }
        return asset('images/default-image-company.webp');
    }
}
