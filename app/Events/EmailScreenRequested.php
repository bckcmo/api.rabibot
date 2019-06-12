<?php

namespace App\Events;

use App\Screen;
use Illuminate\Queue\SerializesModels;

class EmailScreenRequested
{
    use SerializesModels;

    /**
     * @var Screen
     */
    public $screen;

    /**
     * @var array
     */
    public $email;

    /**
     * Create a new event instance.
     *
     * @param Screen $screen
     *
     * @return void
     */
    public function __construct(Screen $screen, string $email)
    {
        $this->screen = $screen;
        $this->email = $email;
    }
}
