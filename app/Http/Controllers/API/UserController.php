<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CompanyUser;
use Illuminate\Http\Request;

class UserController extends Controller
{
// UserController → работает с пользователями (CRUD, профиль, смена пароля, обновление данных).
    public function index(): \Illuminate\Database\Eloquent\Collection
    {
        $this->authorize('viewAny', User::class);

        return User::all();
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);

        return $user->load('companies');
    }

    public function assignToCompany(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $data = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'role' => 'required|in:superadmin,admin,company_head,user',
        ]);

        CompanyUser::updateOrCreate(
            [
                'user_id' => $user->id,
                'company_id' => $data['company_id'],
            ],
            [
                'role' => $data['role'],
            ]
        );

        return response()->json(['message' => 'User assigned/role updated']);
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return response()->json(null, 204);
    }
}
