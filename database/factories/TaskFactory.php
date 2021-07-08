<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Tasks;
use Faker\Generator as Faker;

$factory->define(Tasks::class, function (Faker $faker) {
    return [
        'client_id' => 1,
        'project_id' => 1,
        'name' => $faker->name,
    ];
});
