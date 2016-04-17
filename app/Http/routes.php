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
    // collections/relationships
    $api->get('collections/{id}/{relationship}', 'CollectionsController@getRelated');
    $api->get('collections/{id}/relationships/{relationship}', 'CollectionsController@getRelationships');
    $api->delete('collections/{id}/relationships/{relationship}', 'CollectionsController@deleteRelationships');
    // collections/pages
    // $api->get('collections/{id}/pages', 'CollectionsController@getPages');
    // $api->get('collections/{id}/relationships/pages', 'CollectionsController@getRelationships');
    // $api->post('collections', 'CollectionsController@store');
    // $api->patch('collections/{collection}', 'CollectionsController@update');


    // ---------------------------
    // pages
    $api->get('pages', 'PagesController@index');
    $api->get('pages/{id}', 'PagesController@show');
    $api->post('pages', 'PagesController@store');
    $api->patch('pages/{id}', 'PagesController@update');
    $api->delete('pages/{id}', 'PagesController@delete');
    // pages/relationships
    $api->get('pages/{id}/{relationship}', 'PagesController@getRelated');
    $api->get('pages/{id}/relationships/{relationship}', 'PagesController@getRelationships');
    $api->delete('pages/{id}/relationships/{relationship}', 'PagesController@deleteRelationships');
    // pages/collections
    // $api->get('pages/{page}/collections', 'PagesController@getCollections');
    // $api->get('pages/{page}/relationships/collections', 'PagesController@getCollectionsRelationships');
    // // pages/fragments
    // $api->get('pages/{page}/fragments', 'PagesController@getFragments');
    // $api->get('pages/{page}/relationships/fragments', 'PagesController@getFragmentsRelationships');
    // // pages/metadetails
    // $api->get('pages/{page}/metadetails', 'PagesController@getmetadetails');
    // $api->get('pages/{page}/relationships/metadetails', 'PagesController@getMetadetailsRelationships');
    // ---------------------------
    // fragments
    $api->get('fragments', 'FragmentsController@index');
    $api->get('fragments/{id}', 'FragmentsController@show');
    $api->post('fragments', 'FragmentsController@store');
    $api->patch('fragments/{id}', 'FragmentsController@update');
    $api->delete('fragments/{id}', 'FragmentsController@delete');
    // fragments/relationships
    $api->get('fragments/{id}/{relationship}', 'FragmentsController@getRelated');
    $api->get('fragments/{id}/relationships/{relationship}', 'FragmentsController@getRelationships');
    $api->delete('fragments/{id}/relationships/{relationship}', 'FragmentsController@deleteRelationships');
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

    // metadetails/relationships
    $api->get('metadetails/{id}/{relationship}', 'MetadetailsController@getRelated');
    $api->get('metadetails/{id}/relationships/{relationship}', 'MetadetailsController@getRelationships');
    $api->delete('metadetails/{id}/relationships/{relationship}', 'MetadetailsController@deleteRelationships');
    // ---------------------------
    // images
    $api->get('images', 'ImagesController@index');
    $api->get('images/{id}', 'ImagesController@show');
    $api->post('images', 'ImagesController@store');
    $api->patch('images/{id}', 'ImagesController@update');
    $api->delete('images/{id}', 'ImagesController@delete');
    // metadetails/relationships
    $api->get('images/{id}/{relationship}', 'ImagesController@getRelated');
    $api->get('images/{id}/relationships/{relationship}', 'ImagesController@getRelationships');
    $api->delete('images/{id}/relationships/{relationship}', 'ImagesController@deleteRelationships');
});
