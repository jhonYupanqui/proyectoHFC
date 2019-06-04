<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Administrador\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [ 
        'nombre' => $faker->name,
        'apellidos' => $faker->lastName,
        'dni' => $faker->unique()->numberBetween($min = 10000000, $max = 99999999),
        'telefono' => $faker->unique()->numberBetween($min = 1000000, $max = 9999999),
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'username' => $faker->userName,
        'password' => bcrypt('123456789'),
        'remember_token' => Str::random(10),
        'estado' => $faker->randomElement(['A','I']),
    ];
});
 