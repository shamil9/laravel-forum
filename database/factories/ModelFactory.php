<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
 */

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Channel;
use App\Reply;
use App\Thread;
use App\User;

$factory->define(User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name'           => $faker->name,
        'email'          => $faker->unique()->safeEmail,
        'password'       => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'confirmed'      => true,
        'is_admin'       => false,
    ];
});

$factory->define(Channel::class, function (Faker\Generator $faker) {
    $name = $faker->word;

    return [
        'name' => $name,
        'slug' => $name,
    ];
});

$factory->define(Thread::class, function (Faker\Generator $faker) {
    $title = $faker->sentence;

    return [
        'user_id'    => function () {
            return factory('App\User')->create()->id;
        },
        'channel_id' => function () {
            return factory('App\Channel')->create()->id;
        },
        'title'      => $title,
        'body'       => $faker->paragraph,
        'slug'       => $title,
        'locked'     => false,
    ];
});

$factory->define(Reply::class, function (Faker\Generator $faker) {
    return [
        'thread_id' => function () {
            return factory('App\Thread')->create()->id;
        },
        'user_id'   => function () {
            return factory('App\User')->create()->id;
        },
        'body'      => $faker->paragraph(),
    ];
});
