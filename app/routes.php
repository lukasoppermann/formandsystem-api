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

Route::get('/oauth/authorize', array('before' => 'check-authorization-params|auth', function()
{
  // get the data from the check-authorization-params filter
  $params = Session::get('authorize-params');

  // get the user id
  $params['user_id'] = Auth::user()->id;

  // display the authorization form
  return View::make('authorization-form', array('params' => $params));
}));

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
