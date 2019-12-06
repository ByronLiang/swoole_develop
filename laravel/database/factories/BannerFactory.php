<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Banner::class, function (Faker $faker) {
    return [
        'url' => 'https://picsum.photos/240/180/?'.rand(1, 100),
        'status' => rand(0, 1),
    ];
});
