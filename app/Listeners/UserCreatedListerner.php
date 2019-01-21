<?php

namespace App\Listeners;

use App\Events\UserCreatedEvent;

use App\Notifications\UserRegisteredSuccessfully;

class UserCreatedListerner
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
     * @param  UserCreatedEvent  $event
     * @return void
     */
    public function handle(UserCreatedEvent $event)
    {
        $event->user->activation_code = str_random(30).time();
        $event->user->save();
        $event->user->notify(new UserRegisteredSuccessfully());
    }
}
