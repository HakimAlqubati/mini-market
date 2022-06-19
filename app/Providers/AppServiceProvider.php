<?php

namespace App\Providers;

use App\Http\Controllers\Voyager\MyVoyagerAuthController;
use TCG\Voyager\Http\Controllers\VoyagerAuthController;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(VoyagerAuthController::class, MyVoyagerAuthController::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
