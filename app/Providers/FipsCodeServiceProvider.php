<?php

namespace App\Providers;

use App\Bckcmo\FccFipsCoder;
use Illuminate\Support\ServiceProvider;

class FipsCodeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
      $this->app->singleton('FipsCoder', function ($app) {
        return new FccFipsCoder(config('services.fipscoder.endpoint'));
      });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
