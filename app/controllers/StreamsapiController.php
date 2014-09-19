<?php

use Abstraction\Repositories\ContentRepositoryInterface as Content;
use Abstraction\Repositories\StreamRepositoryInterface as Stream;

class StreamsapiController extends BaseApiController {

	protected $models = [];

	/**
	* construct
	*
	* @return void
	*/
	function __construct(Content $content, Stream $stream)
	{
		// call parent constrcutor
		parent::__construct();

		// add stream specific parameters
		$this->parameters['get']['nested'] = 'false';
		$this->parameters['getAccepted']['nested'] = array('true','false');
		$this->parameters['get']['first'] = 'false';
		$this->parameters['getAccepted']['first'] = array('true','false');

		// models?
		$this->models = [
			'stream' => $stream,
			'content' => $content
		];
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return Response::json('Missing parameters, please provide a valid stream name.',404);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		// get parameters
		$parameters = Input::all();
		//
		if( !isset($parameters['parent_id']) || !is_int($parameters['parent_id']) )
		{
			$parameters['parent_id'] = 0;
		}
		if( !isset($parameters['position']) || !is_int($parameters['position']) )
		{
			$parameters['position'] = 0;
		}

		return Response::json(array('article_id',$this->models['stream']->storeStreamItem($parameters)), 200);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($item = null)
	{
		$parameters = $this->getParameters();
		// stream
		if( isset($item) )
		{
			return Response::json($this->models['stream']->getStream($item, $parameters), 200);
		}
		// pages
		else
		{
			return Response::json('Parameter missing stream name',400);
		}

	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id = null)
	{
		if( $id != null )
		{
			$content = $this->models['content']->getPage($id);

			$content->title = Input::get('title');
			$content->data = Input::get('content');

			$content->save();

			return Response::json(array('message' => 'saved'), 200);
		}
		else
		{
			return Response::json(array('message' => 'ID needed to update content.'), 400);
		}
	}

}
