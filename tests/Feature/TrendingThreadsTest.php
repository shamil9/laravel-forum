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

    private $trending;

    protected function setUp()
    {
        parent::setUp();
        $this->trending = new Trending();
        Redis::del($this->trending->cacheKey());

    }


    /** @test */
    function it_increments_thread_view_count_on_each_visit()
    {
        $thread = factory(Thread::class)->create();
        $trending = $this->trending->get();

        $this->assertEmpty($trending);

        $this->get(route('threads.show', [
            'channel' => $thread->channel_id,
            'thread'  => $thread,
        ]));

        $trending = $this->trending->get();

        $this->assertEquals($thread->title, $trending[0]->title);
    }
}
