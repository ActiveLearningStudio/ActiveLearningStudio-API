<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Organization;
use Faker\Generator as Faker;

$factory->define(Organization::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'description' => $faker->paragraph,
        'domain' => $faker->word,
    ];
});
