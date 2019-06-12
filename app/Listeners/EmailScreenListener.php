<?php

namespace App\Listeners;

use App\Providers\ScreenRequestCompleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailScreenListener
{
    /**
     * Handle the event.
     *
     * @param  ScreenRequestCompleted  $event
     * @return void
     */
    public function handle(ScreenRequestCompleted $event)
    {
        EmailScreen::dispatch($event)->onQueue('low');
    }
}
