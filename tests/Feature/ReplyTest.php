<?php

namespace Tests\Feature;

use App\Reply;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ReplyTest extends TestCase
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
            'user_id' => $this->user->id,
            'locked'  => false,
        ]);
        $this->reply = factory('App\Reply')->create([
            'thread_id' => $this->thread->id,
            'user_id'   => $this->user->id,
        ]);
    }

    /** @test */
    public function it_has_an_owner()
    {
        $this->assertInstanceOf('App\User', $this->reply->owner);
    }

    /** @test */
    public function authenticated_user_may_add_replies()
    {
        $this->signIn();

        $this->post(
            route('replies.store', $this->thread),
            $this->thread->toArray()
        );

        $this->get(
            route(
                'threads.show',
                [
                    'thread'  => $this->thread,
                    'channel' => $this->thread->channel,
                ]
            )
        )->assertSee($this->reply->body);

        $this->assertEquals(2, $this->thread->fresh()->replies_count);
    }

    /** @test */
    public function unauthenticated_user_may_not_add_replies()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->post(
            route(
                'replies.store',
                [
                    'thread'  => $this->thread,
                    'channel' => $this->thread->channel,
                ]
            )
        );
    }

    /** @test */
    public function a_reply_requires_body()
    {
        $user = factory(User::class)->create();
        $reply = factory(Reply::class)->make(['body' => null]);

        $this->withExceptionHandling();
        $this->signIn($user);

        $this->post(
            route('replies.store', ['thread' => $this->thread]),
            $reply->toArray()
        )->assertSessionHasErrors('body');
    }

    /** @test */
    public function unauthenticated_user_may_not_delete_replies()
    {
        $this->withExceptionHandling();

        $reply = factory(Reply::class)->create();

        $this->delete(route('replies.destroy', ['thread' => $reply->thread]))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function unauthorized_user_may_not_delete_replies()
    {
        $this->withExceptionHandling();

        $user = factory(User::class)->create();

        $this->signIn($user);

        $this->delete(
            route('replies.destroy', ['thread' => $this->reply->thread->id])
        )->assertStatus(403);

        $this->assertDatabaseHas('replies', ['id' => $this->reply->id]);
    }

    /** @test */
    public function authorized_user_may_delete_replies()
    {
        $this->withExceptionHandling();

        $this->signIn($this->user);

        $this->delete(route('replies.destroy', ['thread' => $this->reply->thread->id]))
            ->assertStatus(302);

        $this->assertDatabaseMissing('replies', ['id' => $this->reply->id]);
    }

    /** @test */
    public function unauthenticated_user_may_not_edit_replies()
    {
        $this->withExceptionHandling();

        $this->patch(route('replies.update', $this->reply))
            ->assertRedirect('login');
    }

    /** @test */
    public function unauthorized_user_may_not_edit_replies()
    {
        $this->withExceptionHandling();

        $user = factory(User::class)->create();
        $this->signIn($user);

        $this->patch(route('replies.update', $this->reply))
            ->assertStatus(403);
    }

    /** @test */
    public function authorized_user_may_edit_replies()
    {
        $this->withExceptionHandling();

        $updatedText = 'foobar';

        $this->signIn($this->user);

        $this->patch(route('replies.update', $this->reply), ['body' => $updatedText])
            ->assertStatus(200);

        $this->assertDatabaseHas('replies', [
            'id'   => $this->reply->id,
            'body' => $updatedText,
        ]);
    }

    /** @test */
    public function user_should_wait_before_adding_multiple_replies()
    {
        $this->withExceptionHandling();

        $user = factory(User::class)->create();
        $reply = factory(Reply::class)->create();

        $this->signIn($user);

        $this->post(
            route('replies.store', ['thread' => $this->thread]),
            $reply->toArray()
        )->assertStatus(302);

        $this->post(
            route('replies.store', ['thread' => $this->thread]),
            $reply->toArray()
        )->assertStatus(403);
    }
}
