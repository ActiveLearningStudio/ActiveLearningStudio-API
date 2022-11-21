<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Playlist;
use Faker\Generator as Faker;

$factory->define(Playlist::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'project_id' => factory('App\Models\Project')->create()->id,
    ];
});
