<?php

namespace App\Ai\Tools;

use App\Models\User;
use Illuminate\Support\Facades\Gate;

class SearchUsersTool
{
    public function handle(User $user, ?string $term = null, ?string $role = null, int $limit = 10): array
    {
        if (Gate::forUser($user)->denies('viewAny', User::class)) return [];
        $company = currentCompany();
        $query = User::query()->with('companies');
        if ($company && !$user->isSuperAdmin()) $query->whereHas('companies', fn ($q) => $q->where('companies.id', $company->id));
        if ($role && $company) $query->whereHas('companies', fn ($q) => $q->where('companies.id', $company->id)->wherePivot('role', $role));
        if ($term) $query->where(fn ($q) => $q->where('first_name', 'like', "%{$term}%")->orWhere('second_name', 'like', "%{$term}%")->orWhere('email', 'like', "%{$term}%"));

        return $query->limit($limit)->get()->filter(fn (User $model) => Gate::forUser($user)->allows('view', $model))->map(fn (User $model) => [
            'id' => $model->id,
            'name' => trim($model->first_name.' '.$model->second_name),
            'email' => $model->email,
            'role' => $company ? $model->roleIn($company) : null,
            'status' => $model->status_account,
        ])->values()->all();
    }
}