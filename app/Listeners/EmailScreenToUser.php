<?php

namespace App\Listeners;

use App\Providers\ScreenRequestCompleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailScreenToUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ScreenRequestCompleted  $event
     * @return void
     */
    public function handle(ScreenRequestCompleted $event)
    {
        //
    }
}
