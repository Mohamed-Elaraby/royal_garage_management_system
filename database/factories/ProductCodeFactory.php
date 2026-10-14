<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ProductCode;
use Faker\Generator as Faker;

$factory->define(ProductCode::class, function (Faker $faker) {
    return [
        'code' => $faker->unique()->numberBetween(1001,1999),
        'name' => $faker->unique()->name(),
    ];
});
