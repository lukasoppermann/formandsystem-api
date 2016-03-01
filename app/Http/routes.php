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
    // ---------------------------
    // collections
    $api->get('collections', 'CollectionsController@index');
    $api->get('collections/{collection}', 'CollectionsController@show');
    // collections/pages
    $api->get('collections/{collection}/pages', 'CollectionsController@getPages');
    $api->get('collections/{collection}/relationships/pages', 'CollectionsController@getPagesRelationships');
    // ---------------------------
    // pages
    $api->get('pages', 'PagesController@index');
    $api->get('pages/{page}', 'PagesController@show');
    // pages/collections
    $api->get('pages/{page}/collections', 'PagesController@getCollections');
    $api->get('pages/{page}/relationships/collections', 'PagesController@getCollectionsRelationships');
    // pages/fragments
    $api->get('pages/{page}/fragments', 'PagesController@getFragments');
    $api->get('pages/{page}/relationships/fragments', 'PagesController@getFragmentsRelationships');
    // ---------------------------
    // fragments
    $api->get('fragments', 'FragmentsController@index');
    $api->get('fragments/{fragment}', 'FragmentsController@show');
    // fragments/fragments
    $api->get('fragments/{fragment}/fragments', 'FragmentsController@getFragments');
    $api->get('fragments/{fragment}/relationships/fragments', 'FragmentsController@getFragmentsRelationships');
});
