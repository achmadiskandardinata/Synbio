<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontends\HomeController;
use App\Http\Controllers\Backends\DashboardController;


Route::get('/', function () {
    return view('welcome');
});


//User Route
Route::middleware('auth')->group(function() {
    Route::get('/home', [HomeController::class, 'home'])
        ->name('home');
});

//Admin Route
Route::prefix('admin')->middleware('admin')->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])
        ->name('admin.dashboard');
});

require __DIR__ . '/auth-admin.php';
require __DIR__ . '/auth-user.php';
