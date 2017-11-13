<?php

namespace Tests\Feature;

use App\Reply;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guest_connot_favorite_anyting()
    {
        $this->withExceptionHandling()
            ->post(route('favorites.store', 1))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_can_favorite_any_reply()
    {
        $this->signIn();

        $reply = factory(Reply::class)->create();

        $this->post(route('favorites.store', $reply->id));

        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    public function an_authenticated_user_can_unfavorite_any_reply()
    {
        $this->signIn();

        $reply = factory(Reply::class)->create();

        $this->post(route('favorites.store', $reply));

        $this->assertCount(1, $reply->favorites);

        $this->delete(route('favorites.destroy', $reply));

        $this->assertCount(0, $reply->fresh()->favorites);
    }
}
