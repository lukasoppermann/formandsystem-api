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
		// keep because child classes call parent::__con
		// so if its need its easy to add
	}


	function invalidPath(respond $respond)
	{
		return $respond->notFound('Invalid request url');
	}

}
