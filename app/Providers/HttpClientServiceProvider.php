<?php

namespace App\Providers;

use App\Bckcmo\HttpResponse;
use App\Bckcmo\GuzzleHttpClient;
use App\Bckcmo\CurlHttpClient;
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
        return new GuzzleHttpClient(new HttpResponse());
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
