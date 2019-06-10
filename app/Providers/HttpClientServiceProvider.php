<?php

namespace App\Providers;

use App\Bckcmo\GuzzleHttpClient;
use Illuminate\Support\ServiceProvider;

class HttpClientServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
      $this->app->singleton('HttpClient', function ($app) {
        return new GuzzleHttpClient();
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
