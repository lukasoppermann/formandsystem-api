<?php
/*
|--------------------------------------------------------------------------
| DEV - see all DB requests
|--------------------------------------------------------------------------
*/
// \Event::listen('illuminate.query', function($query)
// {
//     \Log::notice($query);
//
// });
/*
|--------------------------------------------------------------------------
| Define API oAuth routes
|--------------------------------------------------------------------------
*/
/*
 * access_token
 */
$router->post('v1/oauth/access_token', function()
{
  return Response::json(Authorizer::issueAccessToken());
});

/*
|--------------------------------------------------------------------------
| Define API v1 routes
|--------------------------------------------------------------------------
*/
$router->group(array('prefix' => 'v1', 'before' => ['oauth']), function($router)
{
  /*
   * Pages resource
   */
	$router->resource('pages', 'PagesApiController', array('except' => array('create', 'edit')));

  /*
   * Stream resource
   */
  $router->resource('streams', 'StreamsApiController', array('except' => array('create', 'edit')));

  /*
   * Wrong paths
   */
   $router->any('/', 'BaseApiController@invalidPath');
});

/*
|--------------------------------------------------------------------------
| Path with wrong version number
|--------------------------------------------------------------------------
*/
$router->any('/{version?}/{wrong?}/{path?}/{given?}', 'BaseApiController@invalidPath');
