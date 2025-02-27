<?php


use Illuminate\Pagination\Paginator;

return [
    App\Providers\AppServiceProvider::class,

    //paginatoion
    Paginator::useBootstrap(),

    Barryvdh\Debugbar\ServiceProvider::class,
];
