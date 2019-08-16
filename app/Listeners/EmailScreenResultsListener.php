<?php

namespace App\Listeners;
use Log;
use App\Mail\ScreenEmail;
use Illuminate\Support\Facades\Mail;
use App\Events\ScreenReportsRecieved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailScreenResultsListener
{
    /**
     * Handle the event.
     *
     * @param  ScreenReportsRecieved  $event
     * @return void
     */
    public function handle(ScreenReportsRecieved $event)
    {
      $message = (new ScreenEmail($event->screen))->onQueue('low');
      Mail::to($event->screen->user->email)->queue($message);
    }
}
