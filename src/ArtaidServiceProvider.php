<?php

namespace Artaskynet\Id;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Request;

class ArtaidServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        Route::post('/auth', '\\Artaskynet\\Id\\Artaid@Auth');
    }
}
