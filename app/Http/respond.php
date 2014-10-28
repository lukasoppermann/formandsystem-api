<?php namespace Formandsystemapi\Http;

use Illuminate\Support\Facades\Response;

class respond{

  protected $statusCode;
  protected $dev_url = "http://dev.formandsystem.com/";

  /**
   * return url to error docs
   *
   * @method error_docs_url
   *
   * @param  int $error_code
   *
   * @return string
   */
  public function errorUrl( $error_code )
  {
    return $this->dev_url.'errors/#'.$error_code;
  }

  /**
   * return info url
   *
   * @method infoUrl
   *
   * @param  string $handle
   *
   * @return string
   */
  public function infoUrl( $handle )
  {
    return $this->dev_url.$handle;
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
   * @param  string $message
   *
   * @return $this->respond
   */
  public function respondWithError($message)
  {
    return $this->respond([
      'success' => false,
      'status_code' => $this->getStatusCode(),
      'more_info' => $this->errorUrl($this->getStatusCode()),
      'error_message' => $message
    ]);
  }

  /**
   * respond with result data
   *
   * @method respondWithData
   *
   * @param  array $data
   */
  public function respondWithData( $data = "", $handle = "" )
  {
    return $this->respond([
      'success' => true,
      'status_code' => $this->getStatusCode(),
      'more_info' => $this->infoUrl($handle),
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
  public function ok( $data = "", $handle = "" )
  {
    return $this->setStatusCode(200)->respondWithData( $data, $handle );
  }

  /**
   * respond without content
   *
   * @method respondNoContent
   */
  public function noContent()
  {
    return $this->setStatusCode(204)->respondWithData();
  }

  /**
   * respond with nad request
   *
   * @method badRequest
   *
   * @param  string $message
   */
  public function badRequest( $message = "Bad Request" )
  {
    return $this->setStatusCode(400)->respondWithError($message);
  }

  /**
   * respond with unauthorized
   *
   * @method unauthorized
   *
   * @param  string $message
   */
  public function unauthorized( $message = "Unauthorized" )
  {
    return $this->setStatusCode(401)->respondWithError($message);
  }

  /**
   * respond with not found error
   *
   * @method notFound
   *
   * @param  string $message
   */
  public function notFound( $message = "Not Found" )
  {
    return $this->setStatusCode(404)->respondWithError($message);
  }

  /**
   * respond with unprocessable content
   *
   * @method unprocessableContent
   *
   * @param  string $message
   */
  public function unprocessableContent( $message = "The request was well-formed but was unable to be followed due to semantic errors." )
  {
    return $this->setStatusCode(422)->respondWithError($message);
  }

  /**
   * respond with internal error
   *
   * @method internalError
   *
   * @param  string $message
   */
  public function internalError( $message = "Internal Error" )
  {
    return $this->setStatusCode(500)->respondWithError($message);
  }

}
