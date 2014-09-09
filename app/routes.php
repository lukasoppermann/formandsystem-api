<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::filter('basic.once', function()
{
    // login
    Auth::onceBasic();
    $user = Auth::user();
    // set db connection
    Config::set("database.connections.user", array(
      'driver'    => 'mysql',
      'host'      => $user->service_host,
      'database'  => $user->service_name,
      'username'  => $user->service_user,
      'password'  => $user->service_key,
      'charset'   => 'utf8',
      'collation' => 'utf8_unicode_ci',
      'prefix'    => '',
    ));
});

Route::get('/', function()
{
	return View::make('docs.index');
});

Route::group(array('prefix' => 'v1', 'before' => array('basic.once')), function()
{
	// invalid url
	Route::get('/', function()
	{
		return Response::json("This url does not exist.", 404);
	});

	// stream api for content
	Route::resource('pages', 'PagesapiController', array('except' => array('create', 'edit')));

  // stream api for content
  Route::resource('streams', 'StreamsapiController', array('except' => array('create', 'edit')));

  // catch add
  Route::any('{path?}', function()
  {
    return View::make('docs.index');
  });
});
