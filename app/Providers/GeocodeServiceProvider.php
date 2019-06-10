<?php

namespace App\Providers;

use App\Bckcmo\GoogleGeocoder;
use Illuminate\Support\ServiceProvider;

class GeocodeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
      $this->app->singleton('Geocoder', function ($app) {
        return new GoogleGeocoder(config('services.geocoder'));
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
