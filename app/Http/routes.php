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
    $api->get('collections/{id}', 'CollectionsController@show');
    $api->post('collections', 'CollectionsController@store');
    $api->patch('collections/{id}', 'CollectionsController@update');
    $api->delete('collections/{id}', 'CollectionsController@delete');
    // collections/pages
    $api->get('collections/{id}/pages', 'CollectionsController@getPages');
    $api->get('collections/{id}/relationships/pages', 'CollectionsController@getPagesRelationships');
    // $api->post('collections', 'CollectionsController@store');
    // $api->patch('collections/{collection}', 'CollectionsController@update');
    $api->delete('collections/{id}/relationships/{relationship}', 'CollectionsController@deleteRelationships');

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
    $api->get('metadetails', 'MetadetailsController@index');
    $api->get('metadetails/{id}', 'MetadetailsController@show');
    $api->post('metadetails', 'MetadetailsController@store');
    $api->patch('metadetails/{id}', 'MetadetailsController@update');
    $api->delete('metadetails/{id}', 'MetadetailsController@delete');
    // metadetails/pages
    $api->get('metadetails/{id}/pages', 'metadetailsController@getPages');
    $api->get('metadetails/{id}/relationships/pages', 'metadetailsController@getPagesRelationships');
    $api->patch('metadetails/{id}/relationships/pages', 'metadetailsController@updateRelationships');
    $api->post('metadetails/{id}/relationships/pages', 'metadetailsController@storeRelationships');
    // $api->delete('metadetails/{id}/relationships/pages', 'metadetailsController@deletePagesRelationships');
    $api->delete('metadetails/{id}/relationships/{relationship}', 'MetadetailsController@deleteRelationships');
    // ---------------------------
    // images
    $api->get('images', 'ImagesController@index');
    $api->get('images/{id}', 'ImagesController@show');
    $api->delete('images/{id}', 'ImagesController@delete');
    // images/fragments
    $api->get('images/{id}/fragments', 'ImagesController@getFragments');
    $api->get('images/{id}/relationships/fragments', 'ImagesController@getFragmentsRelationships');
});
