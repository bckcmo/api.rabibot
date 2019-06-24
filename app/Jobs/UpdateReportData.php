<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Events\ScreenRequested;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateReportData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var App\Events\ScreenRequested
     */
    private $event;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ScreenRequested $event)
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $fipsCoder = resolve('FipsCoder');
        $fipsCoder->setGeoData($this->event->screenData);
        $result = $fipsCoder->fipscode();
        if(!$result['success']) {
          abort($result['status'], 'Fips Coder responded with an error');
        }

        $this->event->screen->one_mile_report = "https://ejscreen.epa.gov/mapper/EJSCREEN_report.aspx?geometry={\"x\":" .
            $this->event->screenData['lng'] . ",\"y\":" . $this->event->screenData['lat'] .
            ",\"spatialReference\":{\"wkid\":4326}}&distance=1&unit=9035&areatype=blockgroup&areaid=&f=report";

        $this->event->screen->blockgroup_report = "https://ejscreen.epa.gov/mapper/EJSCREEN_report.aspx" .
          "?geometry=&distance=&unit=9035&areatype=blockgroup&areaid=" .
          $result['data']['fipscode'] . "&f=report";

        $this->event->screen->save();
    }


    /**
     * Method to get $event.
     *
     * @return App\Events\ScreenRequested
     */
    public function getEvent() {
      return $this->event;
    }
}
