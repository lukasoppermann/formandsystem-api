<?php

/*
|--------------------------------------------------------------------------
| Define API v1 routes
|--------------------------------------------------------------------------
*/
Route::group(array('prefix' => 'v1', 'before' => array('api.auth')), function()
{
	/*
   * Invalid URL /
   */
	Route::any('/', function()
	{
		return Response::json(array('success' => false, 'errors' => array('wrongPath' =>
                          'Wrong request path. For more information read the documentation: http://api.formandsystem.com')),404);
	});

  /*
   * Pages resource
   */
	Route::resource('pages', 'PagesapiController', array('except' => array('create', 'edit')));

  /*
   * Stream resource
   */
  Route::resource('streams', 'StreamsapiController', array('except' => array('create', 'edit', 'destroy')));

  /*
   * Wrong paths
   */
  Route::any('{wrong?}/{path?}/{given?}', function()
  {
    return Response::json(array('success' => false, 'errors' => array('wrongPath' => 'Wrong request path. For more information read the documentation: http://api.formandsystem.com')),404);
  });
});

/*
|--------------------------------------------------------------------------
| Path with wrong version number
|--------------------------------------------------------------------------
*/
Route::any('/{wrongVersion?}/{wrong?}/{path?}/{given?}', function()
{
  return Response::json(array('success' => false, 'errors' => array('wrongPath' =>
          'Wrong request path. For more information read the documentation: http://api.formandsystem.com')),404);
});
