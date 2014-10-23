<?php namespace Formandsystemapi\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;

class BaseApiController extends Controller {

	protected statusCode;

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
		header('Access-Control-Allow-Credentials: true');
		header('Access-Control-Max-Age: 86400');    // cache for 1 day
		header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS");
	}

	/**
	 * return the status code
	 *
	 * @method getStatusCode
	 */
	function getStatusCode()
	{
		return $this->statusCode;
	}

	/**
	 * set the status code
	 *
	 * @method setStatusCode
	 *
	 * @param int $statusCode
	 *
	 * return $this
	 */
	function setStatusCode( $statusCode )
	{
		$this->statusCode = $statusCode;

		return $this;
	}

	/**
	 * return a response
	 *
	 * @method respond
	 *
	 * @param  array or string  $data
	 * @param  array  $headers
	 *
	 * @return Illuminate\Support\Facades\Response
	 */
	function respond($data, $headers = [])
	{
		return Response::json($data, $this->getStatusCode(), $headers);
	}

	/**
	 * return an error response with provided message
	 *
	 * @method responeWithError
	 *
	 * @param  array $message
	 *
	 * @return $this->respond
	 */
	function responeWithError($message)
	{
		return $this->respond([
			'success' => false,
			'error' => [
				'message' => $message,
				'status_code' => $this->getStatusCode()
			]
		]);
	}

	/**
	 * respond with not found error
	 *
	 * @method respondNotFound
	 *
	 * @param  string          $message
	 */
	function respondNotFound( $message = "Not Found" )
	{
		return $this->setStatusCode(404)->respondWithError($message);
	}


}
