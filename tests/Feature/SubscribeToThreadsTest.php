<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SubscribeToThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_subscribe_to_threads()
    {
        $this->signIn();

        $thread = factory(Thread::class)->create();

        $this->post(route('subscription.store', ['thread' => $thread]));

        $this->assertCount(1, $thread->subscriptions);
    }

    /** @test */
    public function a_user_can_unsubscribe_to_threads()
    {
        $this->signIn();

        $thread = factory(Thread::class)->create();

        $this->post(route('subscription.store', ['thread' => $thread]));
        $this->delete(route('subscription.destroy', ['thread' => $thread]));

        $this->assertCount(0, $thread->subscriptions);
    }
}
