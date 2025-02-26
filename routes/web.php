<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontends\HomeController;
use App\Http\Controllers\Backends\DashboardController;
use App\Http\Controllers\Backends\BannerController;
use App\Http\Controllers\Backends\ProductController;


Route::get('/', function () {
    return view('welcome');
});


//User Route
Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'home'])
        ->name('home');
});

//Admin Route
Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])
        ->name('admin.dashboard');

    //Banner Route
    // route::resource('banners', BannerController::class);
    // route::resousce('banners', BannerController::class)->except(['show']);
    Route::get('/banners', [BannerController::class, 'index'])
        ->name('admin.banners.index');
    Route::get('/banners/create', [BannerController::class, 'create'])
        ->name('admin.banners.create');
    Route::post('/banners', [BannerController::class, 'store'])
        ->name('admin.banners.store');
    Route::get('/banners/{banner}/edit', [BannerController::class, 'edit'])
        ->name('admin.banners.edit');
    Route::put('/banners/{banner}', [BannerController::class, 'update'])
        ->name('admin.banners.update');
    Route::delete('/banners/{banner}', [BannerController::class, 'destroy'])
        ->name('admin.banners.destroy');

    //Product Route
    // route::resource('products', ProductController::class);
    // route::resousce('products', ProductController::class)->except(['show']);
    Route::get('/products', [ProductController::class, 'index'])
        ->name('admin.products.index');
    Route::get('/products/create', [ProductController::class, 'create'])
        ->name('admin.products.create');
    Route::post('/products', [ProductController::class, 'store'])
        ->name('admin.products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
        ->name('admin.products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])
        ->name('admin.products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])
        ->name('admin.products.destroy');
});

require __DIR__ . '/auth-admin.php';
require __DIR__ . '/auth-user.php';
