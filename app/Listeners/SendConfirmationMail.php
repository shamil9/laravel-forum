<?php

namespace App\Listeners;

use App\Events\RegisteredUser;
use App\Mail\ConfirmationEmail;
use Illuminate\Support\Facades\Mail;

class SendConfirmationMail
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
     * @param  RegisteredUser $event
     * @return void
     */
    public function handle(RegisteredUser $event)
    {
        Mail::to($event->user)->send(new ConfirmationEmail($event->user));
    }
}
