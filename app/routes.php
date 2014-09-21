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
      return Response::make("Problem loging in", 400);
    }

});

/*
|--------------------------------------------------------------------------
| oAuth routes
|--------------------------------------------------------------------------
*/

Route::post('oauth/access_token', function()
{
    return AuthorizationServer::performAccessTokenFlow();
});



/*
|--------------------------------------------------------------------------
| Generic | docs route
|--------------------------------------------------------------------------
*/

Route::get('/', function()
{
	return View::make('docs.index');
});



/*
|--------------------------------------------------------------------------
| API routes
|--------------------------------------------------------------------------
*/

Route::group(array('prefix' => 'v1', 'before' => array('basic.once')), function()
{
	// invalid url
	Route::get('/', function()
	{
		return View::make('docs.index');
	});

	// stream api for content
	Route::resource('pages', 'PagesapiController', array('except' => array('create', 'edit')));

  // stream api for content
  Route::resource('streams', 'StreamsapiController', array('except' => array('create', 'edit', 'destroy')));

  Route::any('{wrong?}/{path?}/{given?}', function()
  {
    return Response::json('Wrong request path. For more information read the documentation: http://api.formandsystem.com',404);
  });
});
