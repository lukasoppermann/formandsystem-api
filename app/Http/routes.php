<?php
/*
|--------------------------------------------------------------------------
| Define api.formandsystem.com
|--------------------------------------------------------------------------
*/
Route::any('/', function()
{
  return Response::json(array('success' => false, 'errors' => array('wrongPath' =>
                        'Wrong request path. For more information read the documentation: http://dev.formandsystem.com')),404);
});

/*
|--------------------------------------------------------------------------
| Define API oAuth routes
|--------------------------------------------------------------------------
*/
/*
 * access_token
 */
Route::post('oauth/access_token', function()
{
    return Authorizer::issueAccessToken();
});

/*
|--------------------------------------------------------------------------
| Define API v1 routes
|--------------------------------------------------------------------------
*/
Route::group(array('prefix' => 'v1', 'before' => ['oauth']), function()
{
	/*
   * Invalid URL /
   */
	Route::any('/', function()
	{
		return Response::json(array('success' => false, 'errors' => array('wrongPath' =>
                          'Wrong request path. For more information read the documentation: http://dev.formandsystem.com')),404);
	});

  /*
   * Pages resource
   */
	Route::resource('pages', 'PagesApiController', array('except' => array('create', 'edit')));

  /*
   * Stream resource
   */
  Route::resource('streams', 'StreamsApiController', array('except' => array('create', 'edit', 'destroy')));

  /*
   * Wrong paths
   */
  Route::any('{wrong?}/{path?}/{given?}', function()
  {
    return Response::json(array('success' => false, 'errors' => array('wrongPath' =>
                          'Wrong request path. For more information read the documentation: http://dev.formandsystem.com')),404);
  });
});

/*
|--------------------------------------------------------------------------
| Path with wrong version number
|--------------------------------------------------------------------------
*/
Route::any('/{version}/{wrong?}/{path?}/{given?}', function()
{
  return Response::json(array('success' => false, 'errors' => array('wrongPath' =>
                        'Wrong request path. For more information read the documentation: http://dev.formandsystem.com')),404);
});
