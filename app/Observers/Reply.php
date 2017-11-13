<?php

namespace App\Observers;

use App\Reply;

class ReplyObserver
{

    /**
     * Listen to the Reply deleting event.
     *
     * @param  \App\Reply  $reply
     * @return void
     */
    public function deleting(Reply $reply)
    {
        $reply->favorites()->get()->each->delete();
    }
}
