<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class MentionedInReply extends Notification
{
    protected $reply;

    /**
     * MentionedInReply constructor.
     *
     * @param $reply
     */
    public function __construct($reply)
    {
        $this->reply = $reply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => 'You were mentioned by '
                . $this->reply->owner . ' in ' . $this->reply->thread->title,
        ];
    }
}
