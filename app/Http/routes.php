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
    $api->get('collections/{collection}/test', 'CollectionsController@test');
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
    // pages/metadetails
    $api->get('pages/{page}/metadetails', 'PagesController@getmetadetails');
    $api->get('pages/{page}/relationships/metadetails', 'PagesController@getMetadetailsRelationships');
    // ---------------------------
    // fragments
    $api->get('fragments', 'FragmentsController@index');
    $api->get('fragments/{fragment}', 'FragmentsController@show');
    // fragments/fragments
    $api->get('fragments/{fragment}/fragments', 'FragmentsController@getFragments');
    $api->get('fragments/{fragment}/relationships/fragments', 'FragmentsController@getFragmentsRelationships');
    // fragments/images
    $api->get('fragments/{fragment}/images', 'FragmentsController@getImages');
    $api->get('fragments/{fragment}/relationships/images', 'FragmentsController@getImagesRelationships');
    // ---------------------------
    // metadetails (like settings)
    $api->get('metadetails', 'metadetailsController@index');
    $api->get('metadetails/{metadetails}', 'metadetailsController@show');
    $api->post('metadetails', 'metadetailsController@store');
    $api->delete('metadetails/{metadetails}', 'metadetailsController@delete');
    // metadetails/pages
    $api->get('metadetails/{metadetails}/pages', 'metadetailsController@getPages');
    $api->get('metadetails/{metadetails}/relationships/pages', 'metadetailsController@getPagesRelationships');
    $api->delete('metadetails/{metadetails}/relationships/pages', 'metadetailsController@deletePagesRelationships');
    // ---------------------------
    // images
    $api->get('images', 'ImagesController@index');
    $api->get('images/{image}', 'ImagesController@show');
    // images/fragments
    $api->get('images/{image}/fragments', 'ImagesController@getFragments');
    $api->get('images/{image}/relationships/fragments', 'ImagesController@getFragmentsRelationships');
});
