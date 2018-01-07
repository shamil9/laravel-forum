<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LockedThreadTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_thread_can_be_locked()
    {
        $thread = factory(Thread::class)->create();

        $this->post(route('threads.lock', $thread), $thread->toArray());

        $this->assertTrue($thread->fresh()->locked);
    }

    /** @test */
    function a_thread_can_be_unlocked_only_by_its_owner()
    {
        $thread = factory(Thread::class)->create(['locked' => true]);

        $this->post(route('threads.unlock', $thread), $thread->toArray());

        $this->assertFalse($thread->fresh()->locked);
    }
}
