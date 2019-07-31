<?php

namespace App\Providers;

use App\Mail\ScreenEmail;
use App\Bckcmo\EJScreenApi;
use App\Jobs\UpdateReportData;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobProcessed;

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
      return new EJScreenApi(config('services.ejScreenApi.endpoint'), $app->make('GeoCoder'), resolve('HttpClient'));
    });
  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    Queue::after(function (JobProcessed $event) {
      $job = unserialize($event->job->payload()['data']['command']);
      if($job instanceof UpdateReportData) {
        $screenRequestedEvent = $job->getEvent();
        $message = (new ScreenEmail($screenRequestedEvent->screen))->onQueue('low');
        Mail::to($screenRequestedEvent->requestData['email'])->queue($message);
      }
    });
  }
}
