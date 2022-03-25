<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * @var int
     */
    public const  TOKEN_LIVE_TIME = 3600;

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
     */
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {

        $validator = Validator::make(
            $request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            ]
        );
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     */
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {


        $validator = Validator::make(
            $request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
            ]
        );

        $password = $request->input('password') ?? "";

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(
            array_merge(
                $validator->validated(),
                ['password' => bcrypt($password)]
            )
        );
        return response()->json(
            [
            'message' => 'User successfully registered',
            'user' => $user
            ], 201
        );
    }

    /**
     * Log the user out (Invalidate the token).
     */
    public function logout(): \Illuminate\Http\JsonResponse
    {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     */
    public function refresh(): \Illuminate\Http\JsonResponse
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     */
    protected function createNewToken(string $token): \Illuminate\Http\JsonResponse
    {
        return response()->json(
            [
            'access_token' => $token,
            'token_type' => 'bearer',
            // 'expires_in' => auth()->factory()->getTTL() * 60,
            'expires_in' => time() + self::TOKEN_LIVE_TIME,
            'user' => auth()->user()
            ]
        );
    }
}
