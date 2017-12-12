<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AddAvatarTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function only_authenticated_users_may_add_avatar()
    {
        $this->withExceptionHandling();

        $this->json('post', route('api.avatar.store', 1))
            ->assertStatus(401);
    }

    /** @test */
    function avatar_must_be_an_image()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $this->json(
            'post',
            route('api.avatar.store', auth()->user()),
            ['avatar' => 'some-texts']
        )->assertStatus(422);
    }

    /** @test */
    function user_may_add_avatar()
    {
        $this->withExceptionHandling();

        $this->signIn();
        Storage::fake('public');

        $this->json(
            'post',
            route('api.avatar.store', auth()->user()),
            ['avatar' => $file = UploadedFile::fake()->image('avatar.jpg')]
        );

        $this->assertEquals(
            'avatars/' . $file->hashName(),
            auth()->user()->avatar_path
        );

        Storage::disk('public')->assertExists('avatars/' . $file->hashName());
    }
}
