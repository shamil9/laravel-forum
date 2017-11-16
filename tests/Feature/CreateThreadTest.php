<?php

namespace Tests\Feature;

use App\Channel;
use App\Middleware\Spam;
use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CreateThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;
    protected $user;
    protected $channel;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->channel = factory(Channel::class)->create();
        $this->thread = factory('App\Thread')->create([
            'user_id' => $this->user->id,
            'channel_id' => $this->channel->id,
        ]);
    }

    /** @test */
    public function a_user_can_create_thread()
    {
        $this->be($this->user);

        $this->post(route('threads.store', [
            'channel' => $this->thread->channel->id
        ]), $this->thread->toArray());

        $this->get(route('threads.show', [
            'thread' => $this->thread->id,
            'channel' => $this->channel->id,
        ]))
            ->assertSee($this->thread->title)
            ->assertSee($this->thread->body);
    }

    /** @test */
    public function only_authenticated_user_can_create_threads()
    {
        $this->withExceptionHandling();

        $this->post(route('threads.store', ['channel' => $this->thread->channel->id]));

        $this->get(route('threads.create', ['channel' => $this->thread->channel->id]))
            ->assertRedirect('/login');
    }

    /** @test */
    public function a_thread_requires_title()
    {
        $this->threadRequires(['title' => null])->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_body()
    {
        $this->threadRequires(['body' => null])->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_channel()
    {
        $this->threadRequires(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');
    }

    public function threadRequires($fields)
    {
        $this->withExceptionHandling()->be($this->user);

        return $this->post(
            route('threads.store', ['channel' => $this->thread->channel->id]),
            array_merge($this->thread->toArray(), $fields)
        );
    }
    
    /** @test */
    function it_checks_if_thread_body_contains_spam()
    {
        $this->signIn();

        $thread = factory(Thread::class)->create(['body' => 'Yahoo customer support']);

        $this->expectException(\Exception::class);

        $this->post(
            route('threads.store', ['channel' => $thread->channel_id]),
            $thread->toArray()
        );

    }
}
