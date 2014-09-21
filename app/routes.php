<?php

/*
|--------------------------------------------------------------------------
| Define route filters
|--------------------------------------------------------------------------
*/

Route::filter('basic.once', function()
{
    // login
    Auth::logout();
    Auth::once(['email' => 'lukas@vea.re', 'password' => 'lukas']);
    $user = Auth::user();

    if ( Auth::check() )
    {

      // set db connection
      $db = array(
        'driver'    => 'mysql',
        'host'      => $user->service_host,
        'database'  => $user->service_name,
        'username'  => $user->service_user,
        'password'  => $user->service_key,
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
      );
      Config::set("database.connections.user", $db);
    }
    else
    {
      return Response::json(array('success' => false, 'errors' => array('login' => 'Could not authenticate. For more information read the documentation: http://api.formandsystem.com')),400);
    }

});


Route::group(array('prefix' => 'v1', 'before' => array('basic.once')), function()
{
	// invalid url
	Route::any('/', function()
	{
		return Response::json(array('success' => false, 'errors' => array('wrongPath' => 'Wrong request path. For more information read the documentation: http://api.formandsystem.com')),404);
	});

	// stream api for content
	Route::resource('pages', 'PagesapiController', array('except' => array('create', 'edit')));

  // stream api for content
  Route::resource('streams', 'StreamsapiController', array('except' => array('create', 'edit', 'destroy')));

  Route::any('{wrong?}/{path?}/{given?}', function()
  {
    return Response::json(array('success' => false, 'errors' => array('wrongPath' => 'Wrong request path. For more information read the documentation: http://api.formandsystem.com')),404);
  });
});

Route::any('/{wrongVersion?}/{wrong?}/{path?}/{given?}', function()
{
  return Response::json(array('success' => false, 'errors' => array('wrongPath' => 'Wrong request path. For more information read the documentation: http://api.formandsystem.com')),404);
});
