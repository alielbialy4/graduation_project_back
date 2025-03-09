<?php

namespace App\Modules\Auth\Controllers;

use App\Bll\Utility;
use Illuminate\Support\Str;
use App\Modules\Auth\Models\User;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Handle the incoming request.
     * @throws \Throwable
     */
    public function redirect()
    {
        $driver = Socialite::driver('google')->stateless();
        return $driver->redirect();
    }

    public function callback(): \Illuminate\Http\RedirectResponse
    {

        $driver = Socialite::driver('google')->stateless();

        $providerUser = $driver->user();
        $generateUsername = '@' . Utility::generateUsername($providerUser->getName());

        $user = User::firstOrCreate(
            [
                'email'    => $providerUser->getEmail()
            ],
            [
                'name'        => $providerUser->getName(),
                'password'    => bcrypt(Str::random()),
                'username'    => $generateUsername,
                'verified_at' => now()
            ]
        );
        $token = $user->createToken('user', ['user'], now()->addMinutes(config('sanctum.expiration')))->plainTextToken;
        $token = base64_encode($token);
        return redirect()->to("https://miniverse3d.com/login?access_token=" . $token);
    }
}
