<?php

namespace Tests\Unit;

use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ThreadStatsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_increments_thread_views_count()
    {
        $thread = factory(Thread::class)->create();

        $this->assertEquals(0, $thread->visits()->count());

        $thread->visits()->reset();
        $thread->visits()->record();

        $this->assertEquals(1, $thread->visits()->count());
    }
}
