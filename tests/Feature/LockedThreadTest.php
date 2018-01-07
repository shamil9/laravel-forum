<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LockedThreadTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_thread_can_only_be_locked_by_amdin()
    {
        $user = factory(User::class)->create();
        $thread = factory(Thread::class)->create();

        $this->signIn($user);

        $this->post(route('threads.lock', $thread), $thread->toArray());

        $this->assertFalse($thread->fresh()->locked);

        $user->is_admin = true;
        $this->signIn($user);

        $this->post(route('threads.lock', $thread), $thread->toArray());

        $this->assertTrue($thread->fresh()->locked);
    }

    /** @test */
    public function a_thread_can_be_unlocked_only_by_admin()
    {
        $user = factory(User::class)->create();
        $thread = factory(Thread::class)->create(['locked' => true]);

        $this->signIn($user);

        $this->post(route('threads.unlock', $thread), $thread->toArray());

        $this->assertTrue($thread->fresh()->locked);

        $user->is_admin = true;
        $this->signIn($user);

        $this->post(route('threads.unlock', $thread), $thread->toArray());

        $this->assertFalse($thread->fresh()->locked);
    }

    /** @test */
    public function user_can_not_add_a_reply_to_a_locked_thread()
    {
        $this->signIn();

        $thread = factory(Thread::class)->create(['locked' => true]);
        $reply = factory(Reply::class)->create(['thread_id' => $thread->id]);

        $this->post(route('replies.store', $thread), $reply->toArray())
            ->assertStatus(302)
            ->assertSessionHas('flash', 'Sorry this thread is locked');
    }
}
