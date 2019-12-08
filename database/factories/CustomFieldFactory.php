<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CustomField;
use Faker\Generator as Faker;

$factory->define(CustomField::class, function (Faker $faker) {

    return [
        'model_type' => $faker->word,
        'name' => $faker->word,
        'validation' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
