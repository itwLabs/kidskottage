<?php

namespace App\Http\Controllers\App\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\App\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // use AuthenticatesUsers;
    use ResponseTrait;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function login(LoginRequest $request)
    {
        $login = $request->safe()->only(["email", "password"]);

        // Attempt to authenticate user
        $user = User::where('email', $login["email"])->first();

        if (!$user || !Hash::check($login["password"], $user->password)) {
            return $this->responseError([], 'Invalid credentials', 401);
        }

        // Create token using Passport
        $tokenResult = $user->createToken('api_token');
        $token = $tokenResult->accessToken;

        // Return success response
        return $this->responseSuccess([
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => $tokenResult->token->expires_at
        ], 'Login successful');
    }


    public function logout()
    {
        Auth::guard('app')->logout();
        return $this->responseSuccess([], 'Logout successful');;
    }
}
