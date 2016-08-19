<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function($api){
    $api->group([
        'middleware' => [
            'api.auth',
            App\Http\Middleware\TestingMiddleware::class,
            App\Http\Middleware\CorsMiddleware::class,
        ],
        'namespace' => 'App\Api\V1\Controllers',
    ], function($api){
        // app('config')->set('database.connections.test','test');
        // dd(app('config')->get('database.connections.test'));
        // ---------------------------
        // collections
        $api->get('collections', 'CollectionsController@index');
        $api->get('collections/{id}', 'CollectionsController@show');
        $api->post('collections', 'CollectionsController@store');
        $api->patch('collections/{id}', 'CollectionsController@update');
        $api->delete('collections/{id}', 'CollectionsController@delete');
        // collections/relationships
        $api->get('collections/{id}/{relationship}', 'RelationshipController@getRelated');
        $api->get('collections/{id}/relationships/{relationship}', 'RelationshipController@getRelationships');
        $api->post('collections/{id}/relationships/{relationship}', 'RelationshipController@storeRelationships');
        $api->patch('collections/{id}/relationships/{relationship}', 'RelationshipController@updateRelationships');
        $api->delete('collections/{id}/relationships/{relationship}', 'RelationshipController@deleteRelationships');
        // ---------------------------
        // pages
        $api->get('pages', 'PagesController@index');
        $api->get('pages/{id}', 'PagesController@show');
        $api->post('pages', 'PagesController@store');
        $api->patch('pages/{id}', 'PagesController@update');
        $api->delete('pages/{id}', 'PagesController@delete');
        // pages/relationships
        $api->get('pages/{id}/{relationship}', 'RelationshipController@getRelated');
        $api->get('pages/{id}/relationships/{relationship}', 'RelationshipController@getRelationships');
        $api->post('pages/{id}/relationships/{relationship}', 'RelationshipController@storeRelationships');
        $api->patch('pages/{id}/relationships/{relationship}', 'RelationshipController@updateRelationships');
        $api->delete('pages/{id}/relationships/{relationship}', 'RelationshipController@deleteRelationships');
        // ---------------------------
        // fragments
        $api->get('fragments', 'FragmentsController@index');
        $api->get('fragments/{id}', 'FragmentsController@show');
        $api->post('fragments', 'FragmentsController@store');
        $api->patch('fragments/{id}', 'FragmentsController@update');
        $api->delete('fragments/{id}', 'FragmentsController@delete');
        // fragments/relationships
        $api->get('fragments/{id}/{relationship}', 'RelationshipController@getRelated');
        $api->get('fragments/{id}/relationships/{relationship}', 'RelationshipController@getRelationships');
        $api->post('fragments/{id}/relationships/{relationship}', 'RelationshipController@storeRelationships');
        $api->patch('fragments/{id}/relationships/{relationship}', 'RelationshipController@updateRelationships');
        $api->delete('fragments/{id}/relationships/{relationship}', 'RelationshipController@deleteRelationships');
        // ---------------------------
        // metadetails (like settings)
        $api->get('metadetails', 'MetadetailsController@index');
        $api->get('metadetails/{id}', 'MetadetailsController@show');
        $api->post('metadetails', 'MetadetailsController@store');
        $api->patch('metadetails/{id}', 'MetadetailsController@update');
        $api->delete('metadetails/{id}', 'MetadetailsController@delete');
        // metadetails/relationships
        $api->get('metadetails/{id}/{relationship}', 'RelationshipController@getRelated');
        $api->get('metadetails/{id}/relationships/{relationship}', 'RelationshipController@getRelationships');
        $api->post('metadetails/{id}/relationships/{relationship}', 'RelationshipController@storeRelationships');
        $api->patch('metadetails/{id}/relationships/{relationship}', 'RelationshipController@updateRelationships');
        $api->delete('metadetails/{id}/relationships/{relationship}', 'RelationshipController@deleteRelationships');
        // ---------------------------
        // images
        $api->get('images', 'ImagesController@index');
        $api->get('images/{id}', 'ImagesController@show');
        $api->post('images', 'ImagesController@store');
        $api->patch('images/{id}', 'ImagesController@update');
        $api->delete('images/{id}', 'ImagesController@delete');
        // images/relationships
        $api->get('images/{id}/{relationship}', 'RelationshipController@getRelated');
        $api->get('images/{id}/relationships/{relationship}', 'RelationshipController@getRelationships');
        $api->post('images/{id}/relationships/{relationship}', 'RelationshipController@storeRelationships');
        $api->patch('images/{id}/relationships/{relationship}', 'RelationshipController@updateRelationships');
        $api->delete('images/{id}/relationships/{relationship}', 'RelationshipController@deleteRelationships');
        // ---------------------------
        // uploads
        $api->put('uploads/{id}', 'UploadsController@update');
        // ---------------------------
        // Clients
        $api->get('clients/{id}', function(){
            return 'YESS';
        });
        // $api->get('clients/{id}', 'ClientsController@show');
        $api->post('clients', 'ClientsController@store');
        $api->delete('clients/{id}', 'ClientsController@delete');
        // ---------------------------
        // Details
        $api->post('details', 'DetailsController@store');
        $api->delete('details/{id}', 'DetailsController@delete');
    });
    // no oauth
    $api->group([
        'namespace' => 'App\Api\V1\Controllers',
        'middleware' => [
            App\Http\Middleware\TestingMiddleware::class,
            App\Http\Middleware\CorsMiddleware::class,
        ],
    ], function($api){
        // ---------------------------
        // authentications
        $api->post('tokens', 'TokensController@store');
        $api->patch('tokens/{id}', 'TokensController@update');
        $api->delete('tokens/{id}', 'TokensController@delete');
    });
});
