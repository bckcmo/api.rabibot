<?php

namespace App\Providers;

use App\Bckcmo\EJScreenApi;
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
      $this->app->singleton(EJScreenApi::class, function ($app) {
        return new EJScreenApi(config('services.ejScreenApi.endpoint'), $app->make('GeoCoder'));
      });
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
