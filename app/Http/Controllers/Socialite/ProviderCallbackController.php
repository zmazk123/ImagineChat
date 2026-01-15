<?php

namespace App\Http\Controllers\Socialite;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProviderCallbackController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $githubUser = Socialite::driver('github')->user();

        $user = User::updateOrCreate([
            'provider_id' => $githubUser->id,
        ], 
        [
            'name' => $githubUser->name,
            'email' => $githubUser->email,
            'provider_token' => $githubUser->token,
            'provider_refresh_token' => $githubUser->refreshToken,
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }
}
