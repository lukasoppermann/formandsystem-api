<?php

use Abstraction\Repositories\StreamRepositoryInterface as Stream;

class StreamsapiController extends BaseApiController {

	// Repositories
	protected $stream;

	/**
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
		// get parameters
		$parameters = $this->getParameters('post', ['stream' => null,'position' => 0, 'parent_id' => 0 ]);

		// check if stored successfully
		if( is_numeric($articleId = $this->stream->storeStreamItem($parameters)) )
		{
			return Response::json(array('article_id',$articleId), 200);
		}
		return Response::json(false, 400);

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
			return Response::json(array('message' => 'Error while trying to retrieve records.', 'errors' => $parameters['errors']), 400);
		}

		// return stream
		return Response::json($this->stream->getStream($parameters['stream'], $parameters), 200);

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
			return Response::json(array('message' => 'Error while trying to update the record.', 'errors' => $parameters['errors']), 400);
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

		return Response::json(array('message' => 'saved'), 200);

	}

}
