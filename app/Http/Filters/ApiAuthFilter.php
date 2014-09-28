<?php namespace Formandsystemapi\Http\Filters;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Route;
use Illuminate\Contracts\Auth\Authenticator;

class ApiAuthFilter {

	/**
	* The authenticator implementation.
	*
	* @var Authenticator
	*/
	protected $auth;

	/**
	* Create a new filter instance.
	*
	* @param  Authenticator  $auth
	* @return void
	*/
	public function __construct(Authenticator $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Run the request filter.
	 *
	 * @param  Route  $route
	 * @param  Request  $request
	 * @return mixed
	 */
	public function filter(Route $route, Request $request)
	{
		$this->auth->once(['email' => 'lukas@vea.re', 'password' => 'lukas']);
		\Log::error($this->auth->user());
		$this->auth->logout();
    $this->auth->once(['email' => 'lukas@vea.re', 'password' => 'lukas']);
    $user = $this->auth->user();

    if ( $this->auth->check() )
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
      \Config::set("database.connections.user", $db);

    }
    else
    {
      return Response()->json(array('success' => false, 'errors' => array('login' => ['Could not authenticate. For more information read the documentation: http://api.formandsystem.com'])),400);
    }
	}

}
