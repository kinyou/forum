<?php

use Faker\Generator as Faker;

$factory->define(\App\Reply::class, function (Faker $faker) {
    return [
        'thread_id'=>factory(\App\Thread::class)->create()->id,
        'user_id'=>factory(\App\User::class)->create()->id,
        'body'=>$faker->paragraph
    ];
});
