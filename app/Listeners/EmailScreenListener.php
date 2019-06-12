<?php

namespace App\Listeners;

use App\Events\EmailScreenRequested;
use App\Jobs\EmailScreen;

class EmailScreenListener
{
    /**
     * Handle the event.
     *
     * @param  ScreenRequestCompleted  $event
     * @return void
     */
    public function handle(EmailScreenRequested $event)
    {
        EmailScreen::dispatch($event)->onQueue('low');
    }
}
