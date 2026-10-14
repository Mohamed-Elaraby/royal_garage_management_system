<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->userName,
        'code' => $faker->numberBetween(1001,1999),
        'price' => $faker->numberBetween(10,19),
        'selling_price' => $faker->numberBetween(30,50),
        'quantity' => $faker->numberBetween(5,10),
//        'invoice_no' => $faker->numberBetween(500,1000),
        'user_id' => 1,
        'sub_category_id' => $faker->numberBetween(1,5),
        'branch_id' => $faker->numberBetween(1,6),
    ];
});
