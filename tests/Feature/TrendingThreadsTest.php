<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class TrendingThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();
        Redis::del('trending_threads');
    }


    /** @test */
    function it_increments_thread_view_count_on_each_visit()
    {
        $thread = factory(Thread::class)->create();
        $trending = Redis::zrevrange('trending_threads', 0, 4);

        $this->assertEmpty($trending);

        $this->get(route('threads.show', [
            'channel' => $thread->channel_id,
            'thread'  => $thread,
        ]));

        $trending = Redis::zrevrange('trending_threads', 0, 4);

        $this->assertCount(1, $trending);
    }
}
