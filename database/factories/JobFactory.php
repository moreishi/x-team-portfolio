<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Job;
use Faker\Generator as Faker;

$factory->define(Job::class, function (Faker $faker) {
    return [
        'title' => $faker->paragraph,
        'location' => $faker->city .', '. $faker->country,
        'position' => $faker->paragraph,
        'salary_range' => rand(10000, 100000),
        'details' => $faker->paragraph(rand(5, 10)),
        'views' => rand(10000,100000)
    ];
});
