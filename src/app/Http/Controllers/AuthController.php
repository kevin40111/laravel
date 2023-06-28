<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // email validation check
        // find user's email_verified_at
        $user = User::firstWhere('email', $request->email)->only('email_verified_at');
        if ($user['email_verified_at'] === null) {
            return response()->json(['error' => 'Email not verified'], 403);
        }

        return $this->createNewToken($token);
    }
/**
 * Register a User.
 *
 * @return \Illuminate\Http\JsonResponse
 */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        event(new Registered($user));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user,
        ], 201);
    }
/**
 * Log the user out (Invalidate the token).
 *
 * @return \Illuminate\Http\JsonResponse
 */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
/**
 * Refresh a token.
 *
 * @return \Illuminate\Http\JsonResponse
 */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }
/**
 * Get the authenticated User.
 *
 * @return \Illuminate\Http\JsonResponse
 */
    public function userProfile()
    {
        return response()->json(auth()->user());
    }
/**
 * Get the token array structure.
 *
 * @param  string $token
 *
 * @return \Illuminate\Http\JsonResponse
 */
    protected function createNewToken($token)
    {
        $userData = auth()->user()->select('id', 'role', 'fullName', 'username', 'email')->get();
        if ($userData) {
            $userData = $userData[0];
        }
        return response()->json([
            'accessToken' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'userData' => $userData,
        ]);
    }
}
