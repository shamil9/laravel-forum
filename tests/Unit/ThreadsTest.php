<?php

namespace Tests\Unit;

use App\Channel;
use App\Notifications\ThreadUpdated;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->thread = factory('App\Thread')->create([
            'user_id' => $this->user->id,
            'channel_id' => factory(Channel::class)->create()->id
        ]);
    }

    /** @test */
    public function a_thread_has_replies()
    {
        $this->assertInstanceOf(
            'Illuminate\Database\Eloquent\Collection',
            $this->thread->replies
        );
    }

    /** @test */
    public function a_thread_has_an_owner()
    {
        $this->assertInstanceOf('App\User', $this->thread->owner);
    }

    /** @test */
    public function a_thread_has_a_channel()
    {
        $this->assertInstanceOf('App\Channel', $this->thread->channel);
    }

    /** @test */
    public function a_thread_can_be_subscribed_to()
    {
        $thread = factory(Thread::class)->create();

        $thread->subscribe($userId = 1);

        $this->assertEquals(
            1,
            $thread->subscriptions()->where(['user_id' => $userId])->count()
        );
    }

    /** @test */
    public function subscribed_users_should_be_notified_when_a_new_reply_is_added()
    {
        Notification::fake();

        $this->signIn();

        $thread = factory(Thread::class)->create();

        $thread->subscribe()
            ->addReply([
                'user_id' => 1,
                'body' => 'foobar'
            ]);

        Notification::assertSentTo(auth()->user(), ThreadUpdated::class);
    }
}
