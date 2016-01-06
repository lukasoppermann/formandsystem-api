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
/*
| Collection
*/
$factory->define(App\Api\V1\Models\Collection::class, function ($faker) {
    $name = $faker->words(rand(1,2), true);
    return [
        'id' => $faker->uuid,
        'name' => $name,
        'slug' => str_replace(' ','-',$name),
    ];
});
/*
| Page
*/
$factory->define(App\Api\V1\Models\Page::class, function ($faker) {
    return [
        'id'                => $faker->uuid,
        'menu_label'        => $pageName = $faker->words(rand(1,2), true),
        'title'             => $faker->sentence(4),
        'description'       => $faker->paragraph(4),
        'slug'              => str_replace(' ','-',$pageName),
        'published'         => (int)$faker->boolean(80),
        'language'          => $faker->randomElement(['de', 'en']),
    ];
});

/*
| Fragments
*/
$factory->define(App\Api\V1\Models\Fragment::class, function ($faker) {
    return [
        'id' => $uuid = $faker->uuid,
        'type' => $faker->randomElement(['text', 'quote']),
        'name' => (rand(0,1) === 1 ? $faker->word : null),
        'data' => $faker->paragraph(4),
    ];
});

$factory->defineAs(App\Api\V1\Models\Fragment::class, 'section', function ($faker) {
    return [
        'id' => $faker->uuid,
        'type' => 'section',
        'name' => (rand(0,1) === 1 ? $faker->word : null),
        'data' => null,
    ];
});

// for($i=rand(1,5); $i > 0; $i--){
//     for($a=rand(1,2); $a > 0; $a--){
//         $section = new App\Api\V1\Models\Fragment;
//         $section->id = $faker->uuid;
//         $section->type = "section";
//         $section->save();
//
//         $fragments[] = App\Api\V1\Models\Fragment::where('type','section')->get();
//         DB::table('fragmentables')->insert([
//             'fragment_id' => $section->id,
//             'fragmentable_id' => $pageUuid,
//             'fragmentable_type' => 'App\Api\V1\Models\Page',
//         ]);
//         for($a=rand(1,5); $a > 0; $a--){
//             $innerfragments = App\Api\V1\Models\Fragment::where('type', '!=','section')->get();
//             $fragment = $innerfragments[rand(0,count($innerfragments)-1)];
//             DB::table('fragmentables')->insert([
//                 'fragment_id' => $fragment->id,
//                 'fragmentable_id' => $section->id,
//                 'fragmentable_type' => 'App\Api\V1\Models\Fragment',
//             ]);
//         }
//     }
// }
