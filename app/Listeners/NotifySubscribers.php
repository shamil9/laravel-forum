<?php

namespace App\Listeners;

use App\Events\ThreadReceivedNewReply;
use App\Notifications\ThreadUpdated;

class NotifySubscribers
{
    /**
     * Handle the event.
     *
     * @param  ThreadReceivedNewReply $event
     * @return void
     */
    public function handle(ThreadReceivedNewReply $event)
    {
        foreach ($event->reply->thread->subscriptions as $subscription) {
            if ($subscription->user_id != $event->reply->user_id) {
                $subscription->user->notify(new ThreadUpdated($this, $event->reply));
            }
        }
    }
}
