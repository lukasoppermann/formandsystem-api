<?php namespace Formandsystemapi\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;

class BaseApiController extends Controller {

	protected $statusCode;
	protected $error_docs_url = "http://dev.formandsystem.com/errors";

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
	public function getStatusCode()
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
	public function setStatusCode( $statusCode )
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
	public function respond($data, $headers = [])
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
	public function respondWithError($message)
	{
		return $this->respond([
			'success' => false,
			'status_code' => $this->getStatusCode(),
			'error_message' => $message,
			'more_info' => $this->error_docs_url.'#'.$this->getStatusCode()
		]);
	}

	/**
	 * respond with not found error
	 *
	 * @method respondNotFound
	 *
	 * @param  string $message
	 */
	public function respondNotFound( $message = "Not Found" )
	{
		return $this->setStatusCode(404)->respondWithError($message);
	}

	/**
	 * respond with interal error
	 *
	 * @method respondInternalError
	 *
	 * @param  string $message
	 */
	public function respondInternalError( $message = "Internal Error" )
	{
		return $this->setStatusCode(500)->respondWithError($message);
	}

	/**
	 * respond with result data
	 *
	 * @method respondWithData
	 *
	 * @param  array $data
	 */
	public function respondWithData( $data = "" )
	{
		return $this->respond([
			'success' => true,
			'status_code' => $this->getStatusCode(),
			'data' => $data
		]);
	}

	/**
	 * respond with resulted data when ok
	 *
	 * @method respondOk
	 *
	 * @param  array $data
	 */
	public function respondOk( $data = "" )
	{
		return $this->setStatusCode(200)->respondWithData( $data );
	}

	/**
	 * respond without content
	 *
	 * @method respondNoContent
	 */
	public function respondNoContent()
	{
		return $this->setStatusCode(204)->respondWithData();
	}

}
