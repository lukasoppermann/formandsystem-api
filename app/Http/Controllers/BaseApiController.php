<?php namespace Formandsystemapi\Http\Controllers;

use Illuminate\Routing\Controller;

class BaseApiController extends Controller {

	/**
	* construct
	*
	* @return void
	*/
	function __construct()
	{
		// header('Access-Control-Allow-Origin: '.Auth::user()->service_url);
		// header('Access-Control-Allow-Origin: http://cms.formandsystem.com');
	  // Allow from any origin
		header('content-type: application/json; charset=utf-8');
		header('Access-Control-Allow-Origin: http://cms.formandsystem.com');
		header('Access-Control-Allow-Credentials: true');
		header('Access-Control-Max-Age: 86400');    // cache for 1 day
		header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS");

    // Access-Control headers are received during OPTIONS requests
    // if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    //
    //     if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
    //         header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS");
    //
    //     if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
    //         header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    //
    //     exit(0);
    // }
	}


}
