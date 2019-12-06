<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Models\User::class, function (Faker $faker) {
    return [
        //http://i.pravatar.cc/300
        'avatar' => 'https://picsum.photos/128/128/?'.rand(1, 100),
        'nickname' => $faker->name,
        'account' => rand(1, 2) == 1 ? $faker->unique()->email : $faker->unique()->phoneNumber,
        'password' => 123456,
        'remember_token' => str_random(10),
    ];
});
