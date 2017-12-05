<?php

namespace App\Listeners;

use App\Events\ThreadReceivedNewReply;
use App\Notifications\MentionedInReply;
use App\User;

class NotifyMentionedUsers
{
    /**
     * Handle the event.
     *
     * @param  ThreadReceivedNewReply $event
     * @return void
     */
    public function handle(ThreadReceivedNewReply $event)
    {
        // Notify mentioned users
        foreach ($event->reply->mentionedUsers() as $name) {
            $user = User::whereName($name)->first();
            if ($user) {
                $user->notify(new MentionedInReply($event->reply));
            }
        }
    }
}
