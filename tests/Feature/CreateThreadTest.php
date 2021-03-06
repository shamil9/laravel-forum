<?php

namespace Tests\Feature;

use App\Channel;
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
        $this->thread = factory(Thread::class)->create([
            'user_id' => $this->user->id,
            'channel_id' => $this->channel->id,
        ]);
    }

    /** @test */
    public function a_user_can_create_thread()
    {
        $this->be($this->user);

        $thread = factory(Thread::class)->make();

        $this->post(
            route('threads.store', ['channel' => $this->thread->channel]),
            $thread->toArray()
        );

        $this->get(route('threads.show', [
            'channel' => $thread->channel,
            'thread'  => $thread,
        ]))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
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

    /** @test */
    public function it_checks_if_thread_body_contains_spam()
    {
        $this->signIn($this->user);

        $thread = factory(Thread::class)->create(['body' => 'Yahoo customer support']);

        $this->expectException(\Exception::class);

        $this->post(
            route('threads.store', ['channel' => $thread->channel_id]),
            $thread->toArray()
        );
    }

    /** @test */
    function new_users_should_confirm_email_address_before_adding_threads()
    {
        $this->withExceptionHandling();
        $user = factory(User::class)->create(['confirmed' => false]);

        $this->signIn($user);

        $this->post(
            route('threads.store', [
                'channel' => $this->thread->channel_id,
                'thread'  => $this->thread,
            ]),
            $this->thread->toArray()
        )
            ->assertRedirect(route('all.threads.index'))
            ->assertSessionHas('flash', 'You must confirm your email before creating threads');
    }

    public function threadRequires($fields)
    {
        $this->withExceptionHandling()->be($this->user);

        return $this->post(
            route('threads.store', ['channel' => $this->thread->channel->id]),
            array_merge($this->thread->toArray(), $fields)
        );
    }
}
