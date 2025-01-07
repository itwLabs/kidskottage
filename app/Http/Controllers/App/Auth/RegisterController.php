<?php

namespace App\Http\Controllers\App\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\App\RegisterRequest;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use ResponseTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\Models\User
     */
    function create(RegisterRequest $request)
    {
        $data = $request->safe()->all();
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            // 'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
        ]);
        return $this->responseSuccess([], "User registration success");
    }
}
