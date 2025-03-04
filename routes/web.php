<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontends\HomeController;
use App\Http\Controllers\Backends\DashboardController;
use App\Http\Controllers\Backends\BannerController;
use App\Http\Controllers\Backends\ProductController;
use App\Http\Controllers\Backends\BankController;
use App\Http\Controllers\Backends\CourierController;
use App\Http\Controllers\Backends\UserController;
use App\Http\Controllers\Frontends\HomePageController;
use App\Http\Controllers\Frontends\ProductPageController;

Route::get('/', function () {
    return view('frontends.layouts.app');
});

//Page Route
Route::get('/', [HomePageController::class, 'index'])
    ->name('home.page');

//Product Page Route
Route::get('/products', [ProductPageController::class, 'index'])
    ->name('products.page');
Route::get('/products/detail/{product:slug}', [ProductPageController::class, 'show'])
    ->name('products.detail');



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

    //Bank Route
    //route::resource('banks', BankController::class);
    //route::resousce('banks', BankController::class)->except(['show']);
    Route::get('/banks', [BankController::class, 'index'])
        ->name('admin.banks.index');
    Route::get('/banks/create', [BankController::class, 'create'])
        ->name('admin.banks.create');
    Route::post('/banks', [BankController::class, 'store'])
        ->name('admin.banks.store');
    Route::get('/banks/{bank}/edit', [BankController::class, 'edit'])
        ->name('admin.banks.edit');
    Route::put('/banks/{bank}', [BankController::class, 'update'])
        ->name('admin.banks.update');
    Route::delete('/banks/{bank}', [BankController::class, 'destroy'])
        ->name('admin.banks.destroy');

    //Courier Route
    //route::resource('couriers', CourierController::class);
    //route::resousce('couriers', CourierController::class)->except(['show']);
    Route::get('/couriers', [CourierController::class, 'index'])
        ->name('admin.couriers.index');
    Route::get('/couriers/create', [CourierController::class, 'create'])
        ->name('admin.couriers.create');
    Route::post('/couriers', [CourierController::class, 'store'])
        ->name('admin.couriers.store');
    Route::get('/couriers/{courier}/edit', [CourierController::class, 'edit'])
        ->name('admin.couriers.edit');
    Route::put('/couriers/{courier}', [CourierController::class, 'update'])
        ->name('admin.couriers.update');
    Route::delete('/couriers/{courier}', [CourierController::class, 'destroy'])
        ->name('admin.couriers.destroy');


    //User Route
    // route::resource('users', UserController::class);
    // route::resousce('users', UserController::class)->except(['show']);
    Route::get('/users', [UserController::class, 'index'])
        ->name('admin.users.index');
    Route::get('/users/create', [UserController::class, 'create'])
        ->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])
        ->name('admin.users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])
        ->name('admin.users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])
        ->name('admin.users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->name('admin.users.destroy');
});

require __DIR__ . '/auth-admin.php';
require __DIR__ . '/auth-user.php';
