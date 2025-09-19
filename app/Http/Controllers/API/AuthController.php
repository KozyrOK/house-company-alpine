<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function profile(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json($request->user());
    }

    public function register(Request $request)
    {

    }

    function login(Request $request)
    {

    }

    function logout(Request $request)
    {

    }

}
