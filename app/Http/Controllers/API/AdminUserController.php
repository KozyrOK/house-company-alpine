<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', User::class);

        return User::where('status_account', '!=', 'deleted')->with('companies:id,name')->paginate();
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);

        return $user->load('companies:id,name');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        DB::transaction(function () use ($user) {
            $user->update(['status_account' => 'deleted']);
            DB::table('company_user')
                ->where('user_id', $user->id)
                ->where('status_membership', 'active')
                ->update(['status_membership' => 'deleted']);
        });

        return response()->noContent();
    }

    public function update(Request $request, User $user)
    {
       $this->authorize('update', $user);

       $validated = $request->validate([
            'first_name' => 'sometimes|required|string|max:50',
            'second_name' => 'sometimes|required|string|max:50',
            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => 'sometimes|nullable|string|max:30',
            'status_account' => ['sometimes', Rule::in(['pending', 'active', 'deleted'])],
           ]);

        $user->update($validated);

        return response()->json($user->fresh()->load('companies:id,name'));
    }
}
