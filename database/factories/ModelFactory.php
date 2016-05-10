<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/
$factory->define(App\Api\V1\Models\Client::class, function ($faker) {
    return [
        'id' => $faker->uuid,
        'name' => $name,
        'slug' => str_replace(' ','-',$name),
    ];
});
