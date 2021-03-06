<?php

namespace Tests\Feature;

use App\Thread;
use App\Trending;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class TrendingThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();
        Redis::del(Trending::cacheKey());
    }

    /** @test */
    function it_shows_trending_thread()
    {
        $threads = factory(Thread::class, 3)->create();

        $trending = $threads->first()->trending()->get();

        $this->assertEmpty($trending);

        // Visit all threads
        $threads->map(function ($thread) {
            $this->get(route('threads.show', [
                'channel' => $thread->channel_id,
                'thread'  => $thread,
            ]));
        });

        // Visit last thread once more
        $this->get(route('threads.show', [
            'channel' => $threads->values()->get(2)->channel_id,
            'thread'  => $threads->values()->get(2),
        ]));

        $trending = $threads->first()->trending()->get();

        // Last thread should be on top
        $this->assertEquals($threads->values()->get(2)->title, $trending[0]->title);
    }

    /** @test */
    function deleted_thread_should_be_removed_form_trending_list_as_well()
    {
        $this->signIn($user = factory(User::class)->create());

        $thread = factory(Thread::class)->create(['user_id' => $user->id]);
        $trending = $thread->trending();

        $this->get(route('threads.show', [
            'channel' => $thread->channel_id,
            'thread'  => $thread,
        ]));

        $this->assertCount(1, $trending->get());

        $this->delete(route('threads.show', [
            'channel' => $thread->channel_id,
            'thread'  => $thread,
        ]));

        $this->assertCount(0, $trending->get());
    }
}
