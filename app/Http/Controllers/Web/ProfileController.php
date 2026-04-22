<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{

    public function edit(Request $request)
    {
        $user = $request->user()->load('companies:id,name');

        return view('pages.dashboard', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'second_name' => 'required|string|max:50',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => 'nullable|string|max:30',
            'status_account' => ['nullable', Rule::in(['pending', 'active', 'blocked'])],
            'avatar' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,webp,avif|max:5120',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
                Storage::disk('public')->delete($user->avatar_path);
            }

            $validated['avatar_path'] = $request->file('avatar')->store('avatars', 'public');
        }

        unset($validated['avatar']);

        $user->update($validated);

        return redirect()->route('dashboard')->with('status', 'Profile updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        Post::query()->where('updated_by', $user->id)->update(['updated_by' => null]);
        Post::query()->where('deleted_by', $user->id)->update(['deleted_by' => null]);
        Post::query()->where('created_by', $user->id)->delete();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('info')->with('status', 'Account deleted');
    }
}
