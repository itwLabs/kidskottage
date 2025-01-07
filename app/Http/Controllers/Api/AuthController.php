<?php

namespace App\Http\Controllers\Api;

use App\Http\Api\Requests\ForgotPasswordRequest;
use App\Http\Api\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\Repositories\Api\AuthRepository;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    use ResponseTrait;

    public function __construct(private AuthRepository $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Authentication"},
     *     summary="Login to system.",
     *     description="Login",
     *     operationId="login",
     *     @OA\RequestBody(
     *         description="Pet object that needs to be added to the store",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     description="User email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="User password",
     *                     type="string"
     *                 ),
     *                  required = {"email", "password"}
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $data = $this->auth->login($request->all());
            return $this->responseSuccess($data, "Logged in successfully.");
        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage());
        }
    }
    /**
     * @OA\Post(
     *     path="/api/forgot-password",
     *     tags={"Authentication"},
     *     summary="Forgot password",
     *     description="Forgot password",
     *     operationId="forgotPassword",
     *     @OA\RequestBody(
     *         description="Pet object that needs to be added to the store",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     description="User email",
     *                     type="string"
     *                 ),
     *                  required = {"email"}
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $status = Password::broker("admins")->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return $this->responseSuccess([], "Please check your email for reset link");
        }
        return $this->responseError([], "Unable to reset yout password");
    }

    /**
     * @OA\Post(
     *     path="/api/reset-password",
     *     tags={"Authentication"},
     *     summary="Reset password",
     *     description="Reset password",
     *     operationId="resetPassword",
     *     @OA\RequestBody(
     *         description="Pet object that needs to be added to the store",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     description="User email",
     *                     type="string"
     *                 ),
     *                  @OA\Property(
     *                     property="password",
     *                     description="Password",
     *                     type="string"
     *                 ),
     *                  @OA\Property(
     *                     property="password_confirmation",
     *                     description="Confirm Password",
     *                     type="string"
     *                 ),
     *                  @OA\Property(
     *                     property="token",
     *                     description="Reset Token",
     *                     type="string"
     *                 ),
     *                  required = {"email","password","password_confirmation","token"}
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = Password::broker("admins")->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );
        if ($status === Password::PASSWORD_RESET) {
            return $this->responseSuccess([], "Your password has been succefully changes");
        }
        return $this->responseError([], "Unable to reset your password", 422);
    }
}
