<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tag;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Tag::class, function (Faker $faker) {

    $name = Str::random(rand(3,5));
    $slug = Str::slug($name);

    return [
        'name' => $name,
        'slug' => $slug
    ];
});
