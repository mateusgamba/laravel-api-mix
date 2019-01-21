<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PasswordReset extends Model
{
    use Notifiable;

    protected $fillable = [
        'email', 'token'
    ];

    protected $dispatchesEvents = [
        'created' => \App\Events\PasswordResetUpdatedCreatedEvent::class,
        'updated' => \App\Events\PasswordResetUpdatedCreatedEvent::class,
    ];
}
