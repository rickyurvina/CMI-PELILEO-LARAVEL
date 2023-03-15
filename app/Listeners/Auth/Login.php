<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Login as ILogin;

class Login
{

    /**
     * Handle the event.
     *
     * @param ILogin $event
     * @return void
     */
    public function handle(ILogin $event)
    {

        // Save user login time
        $event->user->last_logged_in_at = now();

        $event->user->save();
    }
}
