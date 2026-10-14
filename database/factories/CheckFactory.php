<?php

/** @var Factory $factory */

use App\Models\Check;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Check::class, function (Faker $faker) {
    return [
        'check_number'              => $faker->randomNumber(6),
        'counter_number'            => $faker->randomNumber(6),
        'chassis_number'            => $faker->randomNumber(6),
        'plate_number'              => $faker->randomNumber(6),
        'car_color'                 => $faker->colorName,
        'fuel_level'                => mt_rand(1,100),
        'car_status_report'         => $faker->text(1000),
        'check_status_id'           => mt_rand(1,6),
        'car_type'                  => 'BMW',
        'car_size'                  => $faker->text(5),
        'car_model'                 => $faker->text(5),
        'car_engine'                => $faker->text(5),
        'car_development_code'      => $faker->text(5),
        'user_id'                   => mt_rand(1,2),
//        'technical_id'              => mt_rand(1,10),
        'engineer_id'               => mt_rand(1,10),
        'branch_id'                 => 1,
    ];
});
