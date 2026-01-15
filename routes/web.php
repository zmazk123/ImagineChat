<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Socialite\ProviderRedirectController;
use App\Http\Controllers\Socialite\ProviderCallbackController;
use App\Http\Controllers\ChatController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/sse', [ChatController::class, 'stream'])->middleware(['auth', 'verified']);

Route::get('/auth/github/redirect', ProviderRedirectController::class)->name('auth.redirect');
Route::get('/auth/github/callback', ProviderCallbackController::class)->name('auth.callback');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
