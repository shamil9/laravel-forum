<?php

namespace Tests\Feature;

use App\Reply;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserNotificationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function user_should_be_notified_if_he_is_mentioned_in_a_reply()
    {
        // Users JohnDoe and JaneDoe
        $john = factory(User::class)->create(['name' => 'JohnDoe']);
        $jane = factory(User::class)->create(['name' => 'JaneDoe']);

        // John mentions Jane in a reply
        $this->signIn($john);
        $reply = factory(Reply::class)->create(['body' => 'Hi @JaneDoe']);
        $this->post(
            route('replies.store', $reply->thread),
            $reply->toArray()
        );

        // Jane should receive a notification
        $this->assertCount(1, $jane->notifications);
    }
}
