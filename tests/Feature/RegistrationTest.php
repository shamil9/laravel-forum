<?php

namespace Tests\Feature;

use App\Events\RegisteredUser;
use App\Mail\ConfirmationEmail;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function confirmation_email_is_sent_on_new_user_registration()
    {
        Mail::fake();

        $user = factory(User::class)->create();

        event(new RegisteredUser($user));

        Mail::assertSent(ConfirmationEmail::class);
    }

    /** @test */
    function new_user_can_confirm_account()
    {
        Mail::fake();

        $this->post(
            route('register'),
            [
                'name'                  => 'Joe',
                'email'                 => 'joe@joe.com',
                'password'              => 'foobar',
                'password_confirmation' => 'foobar',
            ]
        );

        $user = User::where('name', 'Joe')->first();

        $this->assertFalse($user->confirmed);

        $this->get(route('confirm', ['token' => $user->confirmation_token]));

        $this->assertTrue($user->fresh()->confirmed);
    }
}
