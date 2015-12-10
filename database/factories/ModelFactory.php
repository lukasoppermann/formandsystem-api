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
$factory->define(App\Api\V1\Models\Collection::class, function ($faker) {
    $name = implode(' ',$faker->words(rand(1,2)));
    return [
        'id' => $faker->uuid,
        'name' => $name,
        'slug' => str_replace(' ','-',$name),
    ];
});

$factory->define(App\Api\V1\Models\Page::class, function ($faker) {
    $pageName = implode(' ',$faker->words(rand(1,2)));
    $pageUuid = $faker->uuid;
    $lang = ['de', 'en'];

    for($i=rand(1,5); $i > 0; $i--){
        for($a=rand(1,7); $a > 0; $a--){
            $fragments[] = App\Api\V1\Models\Fragment::all()->random()->id;
            DB::table('fragment_page')->insert([
                'page_id' => $pageUuid,
                'fragment_id' => end($fragments)
            ]);
        }
        $pageDataArray[$i] = [
            'class' => 'section-0'.$i,
            'fragments' => $fragments,
        ];
    }
    ksort($pageDataArray);

    return [
        'id' => $pageUuid,
        'menu_label' => $pageName,
        'slug' => str_replace(' ','-',$pageName),
        'published' => (int)$faker->boolean(80),
        'language' => $faker->randomElement($lang),
        'data' =>  json_encode(array_values($pageDataArray)),
    ];
});

$factory->define(App\Api\V1\Models\Fragment::class, function ($faker) {
    $fragmentTypes = ['text', 'quote'];
    return [
        'id' => $faker->uuid,
        'type' => $faker->randomElement($fragmentTypes),
        'name' => (rand(0,1) === 1 ? $faker->word : null),
        'data' => $faker->paragraph(4),
    ];
});

$factory->define(App\Api\V1\Models\Page_fragment::class, function ($faker) {
    return [
        'page_id' => App\Api\V1\Models\Page::all()->random()->id,
        'fragment_id' => App\Api\V1\Models\Fragment::all()->random()->id,
    ];
});
