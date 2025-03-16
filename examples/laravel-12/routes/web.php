<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialMediaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-social-posting', [SocialMediaController::class, 'test'])
    ->name('social.test');
