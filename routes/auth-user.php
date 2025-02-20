<?php

use App\Http\Controllers\Frontends\AuthController;
use Illuminate\Support\Facades\Route;

route::middleware('guest')->group(function () {
    route::get('login', [AuthController::class, 'login'])
        ->name('login');

    Route::post('login', [AuthController::class, 'login_post']);

    route::get('register', [AuthController::class, 'register'])
        ->name('register');

    route::post('register', [AuthController::class, 'register_post']);

    route::get('forgot-Password', [AuthController::class, 'forgotPassword'])
        ->name('forgotPassword');

    route::post('forgot-Password', [AuthController::class, 'forgotPassword_post'])
    ->name('forgot-Password.email');

    route::get('/resetPassword/{token}', [AuthController::class, 'resetPassword'])
        ->name('resetPassword');

    route::post('/resetPassword', [AuthController::class, 'resetPassword_post'])
        ->name('resetPassword.post');
});

route::middleware('auth')->group(function () {
    route::match(['get', 'post'], 'logout', [AuthController::class, 'logout'])
        ->name('logout');
});
