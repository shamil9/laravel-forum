<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProfilesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_has_a_profile()
    {
        $user = factory(User::class)->create();

        $this->get(route('profile.show', $user))
            ->assertSee($user->name);
    }

    /** @test */
    public function user_profile_contains_all_associated_threads()
    {
        $this->signIn();

        $thread = factory(Thread::class)->create([
            'user_id' => auth()->id()
        ]);

        $this->get(route('profile.show', auth()->user()))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
