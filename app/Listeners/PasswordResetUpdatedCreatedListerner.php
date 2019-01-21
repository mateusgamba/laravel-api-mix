<?php

namespace App\Listeners;

use App\Events\PasswordResetUpdatedCreatedEvent;

use App\Notifications\ResetPassword;

class PasswordResetUpdatedCreatedListerner
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
     * @param  PasswordResetUpdatedCreatedEvent  $event
     * @return void
     */
    public function handle(PasswordResetUpdatedCreatedEvent $event)
    {
        $event->password->notify(new ResetPassword());
    }
}
