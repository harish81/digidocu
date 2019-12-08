<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'email' => $faker->word,
        'username' => $faker->word,
        'address' => $faker->word,
        'description' => $faker->text,
        'password' => $faker->word,
        'status' => $faker->word
    ];
});
