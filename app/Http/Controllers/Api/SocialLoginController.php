<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function facebookRedirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function loginWithFacebook(): RedirectResponse
    {
        $user = Socialite::driver('facebook')->stateless()->user();

        $findUser = User::where('provider_id', $user->id)->first();

        if ($findUser) {
            Auth::login($findUser);
            return to_route('welcome');
        }

        $new_user = User::create([
            'name' => $user->name,
            'email' => $user->email,
            'provider_id' => $user->id,
            'provider_type' => 'facebook',
            'email_verified_at' => now(),
            'password' => bcrypt('fb_user')
        ]);

        Auth::login($new_user);
        return to_route('welcome');
    }

    public function googleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function loginWithGoogle(): RedirectResponse
    {
        $user = Socialite::driver('google')->stateless()->user();

        $findUser = User::where('provider_id', $user->id)->first();

        if ($findUser) {
            Auth::login($findUser);
            return to_route('welcome');
        }

        $new_user = User::create([
            'name' => $user->name,
            'email' => $user->email,
            'provider_id' => $user->id,
            'provider_type' => 'google',
            'email_verified_at' => now(),
            'password' => bcrypt('google_user')
        ]);

        Auth::login($new_user);
        
    }
}
