<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login & Issue a new token.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) throw new \Exception(
            !$user ? 'The user with this email was not found.' : 'The password is incorrect.');

        return response()->json(['token' => $user->createToken($request->device_name ?? 'web')->plainTextToken]);
    }

    /**
     * Register & Issue a new token.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        $user = User::create(['name' => $request->name, 'email' => $request->email, 'password' => Hash::make($request->password)]);
        return response()->json(['token' => $user->createToken($request->device_name ?? 'web')->plainTextToken]);
    }

    /**
     * Get the current user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json(['user' => $request->user()]);
    }

    /**
     * Delete the token.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->user()->refreshToken(false);
        return response()->json(['message' => 'The token was successfully deleted.']);
    }
}
