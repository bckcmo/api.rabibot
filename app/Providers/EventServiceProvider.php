<?php

namespace App\Providers;

use App\Events\ScreenRequested;
use App\Events\ScreenReportsRecieved;
use App\Listeners\UpdateReportDataListener;
use App\Listeners\EmailScreenResultsListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ScreenRequested::class => [
            UpdateReportDataListener::class,
        ],
        ScreenReportsRecieved::class => [
            EmailScreenResultsListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
