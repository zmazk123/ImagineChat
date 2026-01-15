<?php

namespace App\Http\Controllers\Socialite;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class ProviderRedirectController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        try {
            $a = Socialite::driver('github')->redirect();
            error_log($a);
            return $a;
        } catch (\Exception $e) {
            return redirect()->route('home')
                ->with('error', 'Unable to redirect to GitHub. Please try again later.');
        }
    }
}
