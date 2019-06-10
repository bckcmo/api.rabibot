<?php

namespace App\Providers;

use App\Events\ScreenRequested;
use App\Listeners\UpdateReportData;
use App\Listeners\EmailScreenToUser;
use Illuminate\Support\Facades\Event;
use App\Events\ScreenRequestCompleted;
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
            UpdateReportData::class,
        ],
        ScreenRequestCompleted::class => [
            EmailScreenToUser::class,
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
