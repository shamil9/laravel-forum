<?php

namespace Tests\Feature;

use App\Thread;
use App\Trending;
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
        $thread = factory(Thread::class)->create();

        $trending = $thread->trending()->get();

        $this->assertEmpty($trending);

        $this->get(route('threads.show', [
            'channel' => $thread->channel_id,
            'thread'  => $thread,
        ]));

        $trending = $thread->trending()->get();

        $this->assertEquals($thread->title, $trending[0]->title);
    }
}
