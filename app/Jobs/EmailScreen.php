<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Events\EmailScreenRequested;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class EmailScreen implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var App\Events\EmailScreenRequested
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
        //
    }
}
