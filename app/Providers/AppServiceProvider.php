<?php

namespace App\Providers;

use App\Jobs\UpdateReportData;
use App\Events\ScreenReportsRecieved;
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
        event(new ScreenReportsRecieved($job->getEvent()->screen));
      }
    });
  }
}
