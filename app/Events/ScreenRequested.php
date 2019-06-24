<?php

namespace App\Events;

use App\User;
use App\Screen;
use Illuminate\Queue\SerializesModels;

class ScreenRequested
{
    use SerializesModels;

    /**
     * @var Screen
     */
    public $screen;

    /**
     * @var array
     */
    public $screenData;

    /**
     * @var User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param Screen $screen
     *
     * @return void
     */
    public function __construct(Screen $screen, array $data, User $user)
    {
        $this->screen = $screen;
        $this->screenData = $data;
        $this->user = $user;
    }

}
