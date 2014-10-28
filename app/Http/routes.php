<?php
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
   * Pages resource
   */
	Route::resource('pages', 'PagesApiController', array('except' => array('create', 'edit')));

  /*
   * Stream resource
   */
  Route::resource('streams', 'StreamsApiController', array('except' => array('create', 'edit')));

  /*
   * Wrong paths
   */
   Route::any('/', 'BaseApiController@invalidPath');
});

/*
|--------------------------------------------------------------------------
| Path with wrong version number
|--------------------------------------------------------------------------
*/
Route::any('/{version?}/{wrong?}/{path?}/{given?}', 'BaseApiController@invalidPath');
