<?php

namespace App\Listeners;

use App\Events\ScreenRequested;
use App\Jobs\UpdateReportData;

class UpdateReportDataListener
{
    /**
     * Handle the event.
     *
     * @param  ScreenRequested  $event
     * @return void
     */
    public function handle(ScreenRequested $event)
    {
        UpdateReportData::dispatch($event)->onQueue('high');
    }
}
