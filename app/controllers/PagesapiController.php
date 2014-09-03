<?php

use Abstraction\Repositories\ContentRepositoryInterface as Content;

class PagesapiController extends BaseApiController {

	protected $models = [];

	/**
	* construct
	*
	* @return void
	*/
	function __construct(Content $content)
	{
		// call parent constrcutor
		parent::__construct();

		// add page specific parameters
		$this->parameters['get']['pathSeparator'] = '.';
		$this->parameters['getAccepted']['pathSeparator'] = array('.',':','::','+');

		// models?
		$this->models = [
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
		return "return all pages?";
		// return Response::json($this->models['navigation']->getNested(), 200);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		return Response::json('Store page', 200);
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
		// page
		if( isset($item) )
		{
			return Response::json($this->models['content']->getPage($item, $parameters), 200);
		}
		else
		{
			return Response::json('Parameter missing item id/path',400);
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
	}


}
