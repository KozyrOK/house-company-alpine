<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['profile', 'logout']);
    }

    public function profile(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json($request->user());
    }

    function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'first_name'     => 'required|string|max:255',
            'second_name'      => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'first_name'     => $validated['first_name'],
            'second_name'    => $validated['second_name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'status_account' => 'pending',
        ]);

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'user'    => $user,
            'token'   => $token,
        ], 201);
    }

    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials'],
            ]);
        }

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user]);
    }

//     for social media must provide Laravel Socialite. Functions googleLogin, facebookLogin and xLogin below:

//    public function googleLogin(Request $request)
//    {
//        $googleUser = Socialite::driver('google')->stateless()->user();
//
//        $user = User::where('google_id', $googleUser->id)
//            ->orWhere('email', $googleUser->email)
//            ->first();
//
//        if (! $user) {
//            $user = User::create([
//                'first_name'  => $googleUser->user['given_name'] ?? '',
//                'second_name' => $googleUser->user['family_name'] ?? '',
//                'email'       => $googleUser->email,
//                'google_id'   => $googleUser->id,
//                'image_path'  => $googleUser->avatar,
//                'status'      => 'pending',
//            ]);
//        }
//
//        $token = $user->createToken('api')->plainTextToken;
//
//        return response()->json(['token' => $token, 'user' => $user]);
//    }
//
//    public function facebookLogin(Request $request)
//    {
//        $fbUser = Socialite::driver('facebook')->stateless()->user();
//
//        $user = User::where('facebook_id', $fbUser->id)
//            ->orWhere('email', $fbUser->email)
//            ->first();
//
//        if (! $user) {
//            $user = User::create([
//                'first_name'  => $fbUser->name,
//                'email'       => $fbUser->email,
//                'facebook_id' => $fbUser->id,
//                'image_path'  => $fbUser->avatar,
//                'status'      => 'pending',
//            ]);
//        }
//
//        $token = $user->createToken('api')->plainTextToken;
//
//        return response()->json(['token' => $token, 'user' => $user]);
//    }
//
//    public function xLogin(Request $request)
//    {
//        $xUser = Socialite::driver('twitter')->stateless()->user();
//
//        $user = User::where('x_id', $xUser->id)
//            ->orWhere('email', $xUser->email)
//            ->first();
//
//        if (! $user) {
//            $user = User::create([
//                'first_name' => $xUser->nickname,
//                'email'      => $xUser->email,
//                'x_id'       => $xUser->id,
//                'image_path' => $xUser->avatar,
//                'status'     => 'pending',
//            ]);
//        }
//
//        $token = $user->createToken('api')->plainTextToken;
//
//        return response()->json(['token' => $token, 'user' => $user]);
//    }

}
