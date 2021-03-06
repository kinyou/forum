<?php

use Faker\Generator as Faker;

$factory->define(\App\Thread::class, function (Faker $faker) {
    return [
        'user_id'=>factory(\App\User::class)->create()->id,
        'channel_id'=>factory(\App\Channel::class)->create()->id,
        'title'=>$faker->sentence,
        'body'=>$faker->paragraph
    ];
});
