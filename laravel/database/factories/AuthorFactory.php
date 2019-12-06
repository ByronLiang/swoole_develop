<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Author::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'avatar' => 'https://picsum.photos/128/128/?'.rand(1, 100),
        'introduction' => substr($faker->text, 0, 20),
        'number' => rand(1, 1000),
    ];
});
