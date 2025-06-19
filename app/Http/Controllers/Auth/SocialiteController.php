<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Socialite as ModelSocialite;
use Filament\Notifications\Notification;

class SocialiteController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {

        try {
            $socialUser = Socialite::driver($provider)->user();
            $authuser = $this->store($socialUser, $provider);
            Auth::login($authuser);

            // Notification untuk login berhasil
            Notification::make()
                ->title('Login Berhasil!')
                ->success()
                ->body("Selamat datang, {$authuser->name}! Anda berhasil login dengan {$provider}.")
                ->duration(5000)
                ->send();

            return redirect()->route('filament.apps.pages.dashboard');
        } catch (\Exception $e) {
            // Notification untuk error
            Notification::make()
                ->title('Login Gagal')
                ->danger()
                ->body('Terjadi kesalahan saat login. Silakan coba lagi.')
                ->duration(8000)
                ->send();

            return redirect()->route('login')->with('error', 'Login gagal');
        }
    }

    public function store($socialUser, $provider)
    {
        // Check if the social account already exists
        $socialAccount = ModelSocialite::where('provider_id', $socialUser->getId())
            ->where('provider_name', $provider)
            ->first();

        // If the social account doesn't exist, create a new user and social account
        if (!$socialAccount) {
            // Check if a user with the same email exists
            $user = User::where('email', $socialUser->getEmail())->first();

            // Create the new user with generated username and possibly phone
            $user = User::create([
                'id' => (string) Str::uuid(),
                'name' => $socialUser->getName() ?: $socialUser->getNickname(),
                'email' => $socialUser->getEmail(),
                'password' => bcrypt(Str::random(24)),
            ]);


            // Create the social account
            $socialAccount = $user->socialite()->create([
                'id' => (string) Str::uuid(),
                'provider_id' => $socialUser->getId(),
                'provider_name' => $provider,
                'provider_token' => $socialUser->token,
                'provider_refresh_token' => $socialUser->refreshToken,
            ]);

            return $user;
        }

        // If the social account exists, return the associated user
        return $socialAccount->user;
    }
}
