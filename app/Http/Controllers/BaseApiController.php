<?php namespace Formandsystemapi\Http\Controllers;

use Illuminate\Routing\Controller;
use Formandsystemapi\Http\respond;

class BaseApiController extends Controller {

	/**
	* construct
	*
	* @return void
	*/
	function __construct()
	{
	  // Allow from any origin
		header('content-type: application/json; charset=utf-8');
		// header('Access-Control-Allow-Origin: http://cms.formandsystem.com');
		// header('Access-Control-Allow-Credentials: true');
		header('Access-Control-Max-Age: 86400');    // cache for 1 day
		header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS");
	}


	function invalidPath(respond $respond)
	{
		return $respond->notFound('Invalid request url');
	}

}
