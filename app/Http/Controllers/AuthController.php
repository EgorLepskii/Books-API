<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Lang;
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
     * @OA\Post(
     *     path="/auth/login",
     *     tags={"auth"},
     *     summary="Login user",
     *

     *
     *     @OA\RequestBody (
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/AuthLoginRequest")
     *
     * ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="successful login",
     *         @OA\Schema(
     *             type="JsonResponse",
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Incorrect input data",
     *         @OA\Schema(
     *             type="JsonResponse",
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\Schema(
     *             type="JsonResponse",
     *         ),
     *     ),
     * )
     */
    public function login(Request $request): JsonResponse
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
     * @OA\Post(
     *     path="/auth/register",
     *     tags={"auth"},
     *     summary="Register user",
     *
     *     @OA\RequestBody (
     *        required=true,
     *        @OA\JsonContent(ref="#/components/schemas/AuthRegisterRequest")
     *
     *      ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="User register success",
     *         @OA\Schema(
     *             type="JsonResponse",
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Incorrect input data",
     *         @OA\Schema(
     *             type="JsonResponse",
     *         ),
     *     ),
     * )
     */
    public function register(Request $request): JsonResponse
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
     * @OA\Post(
     *     path="/auth/logout",
     *     summary="User logout",
     *     tags={"auth"},
     *
     *
     *
     *     security={{ "apiAuth": {} }},
     *
     *
     *     @OA\Response(
     *         response=200,
     *         description="User logout success",
     *         @OA\Schema(
     *             type="JsonResponse",
     *         ),
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Already inauthorized",
     *         @OA\Schema(
     *             type="JsonResponse",
     *         ),
     *     ),
     *
     *
     *
     *     )
     * )
     */
    public function logout(): JsonResponse
    {
        auth()->logout();
        return response()->json(['message' => Lang::get('auth.logoutMessage')]);
    }

    /**
     * @OA\Post(
     *     path="/auth/refresh",
     *     summary="Refresh jwt token",
     *     tags={"auth"},
     *
     *
     *
     *     security={{ "apiAuth": {} }},
     *
     *
     *     @OA\Response(
     *         response=200,
     *         description="Token refresh success",
     *         @OA\Schema(
     *             type="JsonResponse",
     *         ),
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized",
     *         @OA\Schema(
     *             type="JsonResponse",
     *         ),
     *     ),
     *     )
     * )
     */
    public function refresh(): JsonResponse
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * @param string $token
     * @return JsonResponse
     */
    protected function createNewToken(string $token): JsonResponse
    {
        return response()->json(
            [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => time() + self::TOKEN_LIVE_TIME,
            'user' => auth()->user()
            ]
        );
    }
}
