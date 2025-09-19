<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CompanyUser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function index(): \Illuminate\Database\Eloquent\Collection
    {
        $this->authorize('viewAny', User::class);

        return User::all();
    }

    /**
     * @throws AuthorizationException
     */
    public function show(User $user): User
    {
        $this->authorize('view', $user);

        return $user->load('companies');
    }

    /**
     * @throws AuthorizationException
     */
    public function assignToCompany(Request $request, User $user): \Illuminate\Http\JsonResponse
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

    /**
     * @throws AuthorizationException
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return response()->json(null, 204);
    }
}
