<?php

namespace Tests\Unit;

use App\Reply;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_returns_names_of_mentioned_users()
    {
        $reply = new Reply(['body' => 'Hi @JaneDoe and @JohnDoe']);

        $this->assertEquals(['JaneDoe', 'JohnDoe'], $reply->mentionedUsers());
    }

    /** @test */
    function it_wraps_the_mentioned_username_with_anchor_tag()
    {
        $this->signIn();
        $reply = new Reply(['body' => 'Hi @JohnDoe']);

        $this->assertEquals(
            'Hi <a href="' . route('profile.show', [auth()->user()]) . '">@JohnDoe</a>',
            $reply->body
        );
    }
}
