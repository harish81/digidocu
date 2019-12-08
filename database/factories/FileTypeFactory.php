<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\FileType;
use Faker\Generator as Faker;

$factory->define(FileType::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'no_of_files' => $faker->randomDigitNotNull,
        'labels' => $faker->word,
        'file_validations' => $faker->word,
        'file_maxsize' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
