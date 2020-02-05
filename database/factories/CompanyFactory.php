<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Company;
use Faker\Generator as Faker;

$factory->define(Company::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'email' => $faker->companyEmail,
        'business_phone' => $faker->phoneNumber,
        'mobile' => $faker->phoneNumber,
        'contact_person' => $faker->name,
        'url' => $faker->url
    ];
});