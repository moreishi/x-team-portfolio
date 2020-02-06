<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Category::class, function (Faker $faker) {

    $title = Str::random(rand(5,16));
    $slug = Str::slug($title);

    return [
        'title' => $title,
        'slug' => $slug
    ];
});
