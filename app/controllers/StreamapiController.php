<?php

class StreamapiController extends BaseController {

	protected $models = [];

	/**
	* construct
	*
	* @return void
	*/
	function __construct()
	{
		$this->models = [
			'navigation' => new Navigation,
			'content' => new Content
		];
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return Response::json($this->models['navigation']->getNested(), 200);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		return Response::json('Yo', 200);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($item = null)
	{
		// default options
		$opts = $defaults = array(
			'format' => 'json',
			'lang' => Config::get('content.locale', 'en')
		);

		// accepted formats
		$formats = array('json');

		// accepted parameters
		$parameters = array('limit','offset','fields','level','depth','lang','language','path','stream','until','since');

		// explode input at . (dot)
		$args = explode(".", $item);
		// if args[0] is set, use as item
		$args[0] !== "" ? $opts['item'] = $args[0] : "";
		// if args[1] is set, use as format if in formats
		$opts['format'] = isset($args[1]) && $args[1] !== "" && in_array($args[1], $formats) ? $args[1] : $formats[0];

		// assign parameters
		foreach(Input::all() as $parameter => $value)
		{
			if( in_array($parameter, $parameters) )
			{
				$opts[$parameter] = $value;
			}
		}

		if( isset($opts['lang']) )
		{
			$opts['language'] = $opts['lang'];
			unset($opts['lang']);
		}
		// merge defaults
		$opts = array_merge($defaults, $opts);

		// set language if given
		if( isset($opts['language']) && $opts['language'] != "" )
		{
			Config::set('content.locale', $opts['language']);
		}

		// check for path parameter
		if( isset($opts['path']) && $opts['path'] != "" )
		{
			$opts['item'] = urldecode($opts['path']);
		}

		// navigation
		if( isset($opts['item']) && $opts['item'] == 'navigation' )
		{
			return Response::json($this->models['navigation']->getNavigation(), 200);
		}
		// page
		elseif( isset($opts['item']) )
		{
			return Response::json($this->models['content']->getPage($opts['item']), 200);
		}
		// pages
		else
		{
			return Response::json($this->models['content']->getContent($opts), 200);
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


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		return Response::json('Yo', 200);
		//		//['config' => ['secretkey' => 'Lukas']
	}


}
