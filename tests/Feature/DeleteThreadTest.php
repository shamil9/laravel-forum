<?php

namespace Tests\Feature;

use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class DeleteThreadTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function authorized_user_can_delete_thread()
    {
        $this->withExceptionHandling();
        $this->signIn();

        $channel = factory(Channel::class)->create();
        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);
        $reply = factory(Reply::class)->create([
            'thread_id' => $thread->id,
            'user_id' => auth()->id(),
        ]);

        $this->delete(route('threads.destroy', [
            'channel' => $channel,
            'thread' => $thread
        ]));

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertDatabaseMissing('activities', [
            'subject_id' => $reply->id,
            'subject_type' => get_class($reply)
        ]);

        $this->assertDatabaseMissing('activities', [
            'subject_id' => $thread->id,
            'subject_type' => get_class($thread)
        ]);
    }

    /** @test */
    public function unauthorized_user_can_not_delete_threads()
    {
        $this->withExceptionHandling();
        $thread = factory(Thread::class)->create();
        $channel = factory(Channel::class)->create();
        $user = factory(User::class)->create();

        $this->delete(route('threads.destroy', [
            'channel' => $channel,
            'thread' => $thread
        ]))->assertRedirect('/login');

        $this->be($user);

        $this->delete(route('threads.destroy', [
            'channel' => $channel,
            'thread' => $thread
        ]))->assertStatus(403);
    }
}
