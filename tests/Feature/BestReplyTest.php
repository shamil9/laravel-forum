<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class BestReplyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_reply_can_be_marked_as_best()
    {
        $this->signIn($user = factory(User::class)->create());

        $thread = factory(Thread::class)->create(['user_id' => $user->id]);
        $replies = factory(Reply::class, 3)->create(['thread_id' => $thread->id]);

        $this->postJson(
            route('best.reply.store', $replies[1]),
            $thread->toArray()
        );

        $this->assertEquals($replies[1]->id, $thread->bestReply()->first()->reply_id);
    }

    /** @test */
    function only_thread_creator_can_assign_best_reply()
    {
        $this->withExceptionHandling();

        $this->signIn($user = factory(User::class)->create());

        $thread = factory(Thread::class)->create(['user_id' => $user->id]);
        $reply = factory(Reply::class)->create(['thread_id' => $thread->id]);

        $this->postJson(
            route('best.reply.store', $reply),
            $thread->toArray()
        )->assertStatus(200);

        $this->signIn();

        $this->postJson(
            route('best.reply.store', $reply),
            $thread->toArray()
        )->assertStatus(403);
    }
}
