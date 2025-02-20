<?php

use App\Http\Controllers\Backends\Auth\AuthController;
use Illuminate\Support\Facades\Route;


route::prefix('admin')->middleware('guest:admin')->group(function () {
    route::get('/login', [AuthController::class, 'login'])
        ->name('admin.login');

    Route::post('login', [AuthController::class, 'login_post']);

    route::get('/forgot-Password', [AuthController::class, 'forgotPassword'])
        ->name('admin.forgotPassword');

    route::post('/forgot-Password', [AuthController::class, 'forgotPassword_post'])
    ->name('admin.forgot-Password.email');

    route::get('/resetPassword/{token}', [AuthController::class, 'resetPassword'])
        ->name('admin.resetPassword');

    route::post('/resetPassword', [AuthController::class, 'resetPassword_post'])
        ->name('admin.resetPassword.post');
});

route::prefix('admin')->middleware('admin')->group(function () {
    route::match(['get', 'post'], 'logout', [AuthController::class, 'logout'])
        ->name('admin.logout');

});
