<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
$api = app('Dingo\Api\Routing\Router');

$api->group([
    'version' => 'v1',
    'namespace' => 'App\Api\V1\Controllers',
], function($api)
{
    // collections
    $api->get('collections', 'CollectionsController@index');
    $api->get('collections/{collection}', 'CollectionsController@show');
    $api->get('collections/{collection}/pages', 'CollectionsController@getPages');
    // pages
    $api->get('pages', 'PagesController@index');
    $api->get('pages/{page}', 'PagesController@show');
    $api->get('pages/{page}/collections', 'PagesController@getCollections');
    $api->get('pages/{page}/relationships/collections', 'PagesController@getRelationshipsCollections');
});
