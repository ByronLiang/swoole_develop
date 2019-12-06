<?php

use Faker\Generator as Faker;

$factory->define(App\Models\User::class, function (Faker $faker) {
    return [
        'nickname' => $faker->name,
        'avatar' => $faker->url,
        'phone' => $faker->phoneNumber,
        'password' => $faker->password,
        'api_token' => $faker->macPlatformToken,
    ];
});
