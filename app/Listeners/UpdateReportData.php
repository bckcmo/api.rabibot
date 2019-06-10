<?php

namespace App\Listeners;

use Log;
use App\Events\ScreenRequested;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateReportData implements ShouldQueue
{
    /**
     * @var FipsCoderInterface
     */
    private $fipsCoder;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->fipsCoder = resolve('FipsCoder');
    }

    /**
     * Handle the event.
     *
     * @param  ScreenRequested  $event
     * @return void
     */
    public function handle(ScreenRequested $event)
    {
        $this->fipsCoder->setGeoData($event->screenData);
        $result = $this->fipsCoder->fipscode();
        if(!$result['success']) {
          // // TODO: Throw exception to re-queue event
        }

        $event->screen->one_mile_report = "https://ejscreen.epa.gov/mapper/EJSCREEN_report.aspx?geometry={\"x\":" .
		        $event->screenData['lng'] . ",\"y\":" . $event->screenData['lat'] .
		        ",\"spatialReference\":{\"wkid\":4326}}&distance=1&unit=9035&areatype=blockgroup&areaid=&f=report";

        $event->screen->blockgroup_report = "https://ejscreen.epa.gov/mapper/EJSCREEN_report.aspx" .
          "?geometry=&distance=&unit=9035&areatype=blockgroup&areaid=" .
          $result['data']['fipscode'] . "&f=report";

        $event->screen->save();
    }
}
