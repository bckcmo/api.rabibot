<?php

namespace App\Policies;

use App\User;
use App\Screen;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScreenPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the screen.
     *
     * @param  \App\User  $user
     * @param  \App\Screen  $screen
     * @return mixed
     */
    public function crud(User $user, Screen $screen)
    {
        return $user->id === $screen->user_id;
    }
}
