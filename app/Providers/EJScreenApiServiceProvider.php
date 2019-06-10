<?php

namespace App\Providers;

use App\Bckcmo\EJScreenApi;
use Illuminate\Support\ServiceProvider;

class EJScreenApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
      $this->app->singleton(EJScreenApi::class, function ($app) {
        return new EJScreenApi(config('services.ejScreenApi.endpoint'), $app->make('Geocoder'));
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
