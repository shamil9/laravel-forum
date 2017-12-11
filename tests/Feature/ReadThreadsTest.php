<?php

namespace Tests\Feature;

use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;
    protected $user;
    protected $reply;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $this->thread = factory('App\Thread')->create([
            'user_id'    => $this->user->id,
            'channel_id' => factory(Channel::class)->create()->id,
        ]);
        $this->reply = factory('App\Reply')->create([
            'thread_id' => $this->thread->id,
            'user_id'   => $this->user->id,
        ]);
    }

    /** @test */
    public function a_user_can_browse_threads()
    {
        $this->get(route('all.threads.index'))
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_view_thread()
    {
        $this->get(route('threads.show', [
            'thread'  => $this->thread->id,
            'channel' => $this->thread->channel->id,
        ]))
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_replies_in_the_thread()
    {
        $this->get(route('threads.show', [
            'thread'  => $this->thread->id,
            'channel' => $this->thread->channel->id,
        ]))
            ->assertSee($this->reply->body);
    }

    /** @test */
    public function a_user_can_filter_threads_by_channel()
    {
        $channel = factory(Channel::class)->create();
        $threadInChannel = factory(Thread::class)->create(['channel_id' => $channel->id]);
        $threadNotInChannel = factory(Thread::class)->create();

        $this->get(route('channels.show', $channel))
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    /** @test */
    public function a_user_can_view_his_own_threads()
    {
        $this->signIn(factory('App\User')->create(['name' => 'Foo']));

        $threadByUser = factory(Thread::class)->create(['user_id' => auth()->id()]);
        $threadNotUser = factory(Thread::class)->create();

        $this->get('threads?by=Foo')
            ->assertSee($threadByUser->title)
            ->assertDontSee($threadNotUser->title);
    }

    /** @test */
    public function a_user_can_filter_threads_by_popularity()
    {
        $threadWithTwoReply = factory(Thread::class)->create();
        factory(Reply::class, 2)->create(['thread_id' => $threadWithTwoReply->id]);

        $threadWithThreeReply = factory(Thread::class)->create();
        factory(Reply::class, 3)->create(['thread_id' => $threadWithThreeReply->id]);

        $response = $this->getJson('threads?popularity=1')->json();

        $this->assertEquals([3, 2, 1], array_column($response['data'], 'replies_count'));
    }

    /** @test */
    public function a_user_can_filter_thread_with_no_replies()
    {
        $firstThread = factory(Thread::class)->create();
        $secondThread = factory(Thread::class)->create();

        factory(Reply::class, 2)->create(['thread_id' => $firstThread->id]);

        $this->get('threads?unanswered=1')
            ->assertSee($secondThread->title)
            ->assertDontSee($firstThread->title);
    }
}
