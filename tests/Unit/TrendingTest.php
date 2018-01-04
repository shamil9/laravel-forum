<?php

namespace Tests\Unit;

use App\Thread;
use App\Trending;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class TrendingTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        Redis::del(Trending::cacheKey());
    }

    /** @test */
    function it_returns_trending_threads()
    {
        $threads = factory(Thread::class, 3)->create();

        $threads->each(function ($thread) {
            $thread->trending()->push($thread);
        });

        $this->assertCount(3, $threads->first()->trending()->get());
    }

    /** @test */
    function it_increments_view_count_of_a_given_thread()
    {
        $threads = factory(Thread::class, 2)->create();

        // Visist first thread once
        $threads[0]->trending()->push($threads[0]);

        // Visist last thread twice
        $threads[1]->trending()->push($threads[1]);
        $threads[1]->trending()->push($threads[1]);

        // Last thread should be on top
        $topTrending = $threads[0]->trending()->get()->first();
        $this->assertEquals(
            $threads->last()->title, $topTrending->title);
    }

    /** @test */
    function it_removes_given_thread()
    {
        $thread = factory(Thread::class)->create();
        $trending = $thread->trending();

        $trending->push($thread);

        $this->assertCount(1, $trending->get());

        $trending->remove($thread);

        $this->assertCount(0, $trending->get());
    }
}
