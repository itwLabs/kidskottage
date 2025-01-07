<?php

namespace App\Repositories\Api;

use App\Models\Admin;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\PersonalAccessTokenResult;

class AuthRepository
{
    public function login(array $data): array
    {
        $user = $this->getAdminByEmail($data['email']);
        if (!$user) {
            throw new Exception("Sorry, Admin does not exist.", 404);
        }

        if (!$this->isValidPassword($user, $data)) {
            throw new Exception("Sorry, password does not match.", 401);
        }
        $tokenInstance = $this->createAuthToken($user);
        return $this->getAuthData($user, $tokenInstance);
    }

    public function register(array $data): array
    {
        $user = Admin::create($this->prepareDataForRegister($data));
        if (!$user) {
            throw new Exception("Sorry, Admin did not register, please try again", 500);
        }

        $tokenInstance = $this->createAuthToken($user);
        return $this->getAuthData($user, $tokenInstance);
    }

    public function getAdminByEmail(string $email): ?Admin
    {
        return Admin::query()->where('email', $email)->first();
    }

    public function isValidPassword(Admin $user, array $data): bool
    {
        return Hash::check($data['password'], $user->password);
    }

    public function createAuthToken(Admin $user): PersonalAccessTokenResult
    {
        return $user->createToken('authToken');
    }

    public function getAuthData(Admin $user, PersonalAccessTokenResult $tokenInstance)
    {
        return [
            'user' => $user,
            'access_token' => $tokenInstance->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($tokenInstance->token->expires_at)->toDateTimeString()
        ];
    }

    public function prepareDataForRegister(array $data): array
    {
        return [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ];
    }


    
}
