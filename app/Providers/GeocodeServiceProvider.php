<?php

namespace App\Providers;

use App\Bckcmo\GoogleGeoCoder;
use Illuminate\Support\ServiceProvider;

class GeoCodeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
      $this->app->singleton('GeoCoder', function ($app) {
        return new GoogleGeoCoder(config('services.geocoder'));
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
