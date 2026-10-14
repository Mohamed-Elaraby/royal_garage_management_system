<?php

/** @var Factory $factory */

use App\Models\Client;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Client::class, function (Faker $faker) {
    return [
        'name' => $faker -> name,
        'phone' => $faker -> phoneNumber,
        'building_number' => $faker -> buildingNumber,
        'street_name' => $faker -> streetName,
        'district' => $faker -> streetName,
        'city' => $faker -> city,
        'country' => $faker -> country,
        'postal_code' => $faker -> postcode,
        'vat_number' => $faker -> creditCardNumber,
    ];
});
