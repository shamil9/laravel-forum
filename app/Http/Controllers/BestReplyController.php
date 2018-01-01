<?php

namespace App\Http\Controllers;

use App\BestReply;
use App\Reply;
use App\Thread;

class BestReplyController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Thread $thread
     * @param Reply  $reply
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Thread $thread, Reply $reply)
    {
        $this->authorize('update', $thread);

        BestReply::create([
            'thread_id' => $thread->id,
            'reply_id'  => $reply->id,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BestReply $bestReply
     * @return \Illuminate\Http\Response
     */
    public function show(BestReply $bestReply)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BestReply $bestReply
     * @return \Illuminate\Http\Response
     */
    public function destroy(BestReply $bestReply)
    {
        //
    }
}
