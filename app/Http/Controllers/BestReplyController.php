<?php

namespace App\Http\Controllers;

use App\BestReply;
use App\Reply;

class BestReplyController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Reply $reply
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Reply $reply)
    {
        $this->authorize('update', $reply->thread);

        if ($reply->thread->bestReply()->exists()) {
            $this->destroy($reply);
        }

        BestReply::create([
            'thread_id' => $reply->thread->id,
            'reply_id'  => $reply->id,
        ]);
    }

    /**
     * Remove the specified resource.
     *
     * @param Reply $reply
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply->thread);

        $reply->thread->bestReply()->delete();
    }
}
