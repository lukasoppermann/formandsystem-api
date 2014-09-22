<?php

use Abstraction\Repositories\StreamRepositoryInterface as Stream;

class StreamsapiController extends BaseApiController {

	// Repositories
	protected $stream;

	/**return
	* construct
	*
	* @return void
	*/
	function __construct(Stream $stream)
	{
		// call parent constrcutor
		parent::__construct();

		// add stream specific parameters
		$this->parameters['get']['nested'] = 'false';
		$this->parameters['getAccepted']['nested'] = array('true','false');
		$this->parameters['get']['first'] = 'false';
		$this->parameters['getAccepted']['first'] = array('true','false');

		// Repositories
		$this->stream = $stream;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return Response::json('Missing parameters, please provide a valid stream name. For more information read the documentation: http://api.formandsystem.com',404);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		// validation for post
		$this->parameters['post']['accepted'] = array(
			'stream' =>'alpha_dash|required',
			'position' => 'integer',
			'parent_id' => 'integer'
		);
		// defaults for post
		$this->parameters['post']['default'] = array(
			'position' => 1,
			'parent_id' => 0
		);

		// get parameters
		$parameters = $this->validateParameters('post', Input::all());

		// if validation fails, return error
		if( isset($parameters['errors']) )
		{
			return Response::json(array('success' => 'false', 'errors' => $parameters['errors']), 400);
		}

		// check if stored successfully
		if( $stream = $this->stream->storeStreamItem($parameters) )
		{
			return Response::json(array('success' => true, 'article_id' => $stream->article_id), 200);
		}

		// error while storing
		return Response::json(array('success' => 'false', 'errors' => array('storing' => 'Error while storing record.')), 400);

	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($stream = null)
	{
		// validation for get
		$this->parameters['get']['accepted'] = array_merge($this->parameters['get']['accepted'], array(
			'stream' =>'alpha_dash|required',
			'position' => 'integer',
			'parent_id' => 'integer'
		));

		// validate input
		$parameters = $this->validateParameters('get', array_merge(array('stream' => $stream),Input::all()));

		// if validation fails, return error
		if( isset($parameters['errors']) )
		{
			return Response::json(array('success' => 'false', 'errors' => $parameters['errors']), 400);
		}

		// return stream
		return Response::json(array_merge(array('success' => 'true'), $this->stream->getStream($parameters['stream'], $parameters)), 200);

	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id = null)
	{
		// validation for put
		$this->parameters['put']['accepted'] = array(
			'id' => 'integer|required',
			'stream' =>'alpha_dash',
			'position' => 'integer',
			'parent_id' => 'integer'
		);
		// get parameters
		$parameters = $this->validateParameters('put', array_merge(
			array( 'id' => $id ),
			Input::all()
		));

		// if validation fails, return error
		if( isset($parameters['errors']) )
		{
			return Response::json(array('success' => 'false', 'errors' => $parameters['errors']), 400);
		}

		// retrieve model
		$streamItem = $this->stream->getById($id);

		// update all changed values
		foreach( $parameters as $key => $value )
		{
			$streamItem->$key = $value;
		}

		// save model
		$streamItem->save();

		return Response::json(array('success' => 'true'), 200);

	}

}
