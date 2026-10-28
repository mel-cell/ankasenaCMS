<?php

namespace App\Http\Controllers;

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $google = Socialite::driver('google')->user();

        // Cocokkan via google_id dulu, lalu email (nyambungkan akun lama).
        $user = User::where('google_id', $google->getId())
            ->orWhere('email', $google->getEmail())
            ->first();

        if (! $user) {
            $user = User::create([
                'name' => $google->getName() ?: $google->getNickname(),
                'email' => $google->getEmail(),
                'google_id' => $google->getId(),
                'avatar_url' => $google->getAvatar(),
                'role' => 'user',
            ]);
        } elseif (! $user->google_id) {
            $user->update(['google_id' => $google->getId(), 'avatar_url' => $user->avatar_url ?: $google->getAvatar()]);
        }

        auth()->login($user, remember: true);

        return redirect(session('url.intended', '/vote'));
    }

    public function logout()
    {
        auth()->logout();

        return redirect('/');
    }
}
