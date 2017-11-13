<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_should_not_be_notified_for_replies_he_made()
    {
        $this->signIn();

        $thread = factory(Thread::class)->create();

        $this->post(route('subscription.store', ['thread' => $thread]));
        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Body text'
        ]);

        $this->assertCount(0, auth()->user()->fresh()->notifications);
    }

    /** @test */
    public function send_notification_when_new_reply_is_added_to_subscribed_thread()
    {
        $this->signIn();

        $thread = factory(Thread::class)->create();

        $this->post(route('subscription.store', ['thread' => $thread]));
        $thread->addReply([
            'user_id' => factory(User::class)->create()->id,
            'body' => 'Body text'
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    /** @test */
    public function a_user_can_clear_notifications()
    {
        $this->signIn();

        $thread = factory(Thread::class)->create();

        $this->post(route('subscription.store', [$thread]));

        $thread->addReply([
            'user_id' => factory(User::class)->create()->id,
            'body' => 'Body text'
        ]);

        $this->assertCount(1, auth()->user()->unreadNotifications);

        $this->get(route('notification.index'));

        $this->assertCount(0, auth()->user()->fresh()->unreadNotifications);
    }
}
