<?php

use Abstraction\Repositories\ContentRepositoryInterface as Content;

class PagesapiController extends BaseApiController {

	// Repositories
	protected $content;

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
		$this->parameters['get']['default']['pathSeparator'] = '.';
		$this->parameters['get']['accepted']['pathSeparator'] = 'in:.,:,::,+';

		// Repositories
		$this->content = $content;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return Response::json('Parameter missing: id or path. For more information read the documentation: http://api.formandsystem.com',400);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		return Response::json($this->content->getPage($item, $parameters), 200);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int $id || string (path)
	 * @return Response
	 */
	public function show($id = null)
	{
		// validation for get
		$this->parameters['get']['accepted'] = array_merge($this->parameters['get']['accepted'], array(
			'id' =>'required',
			'position' => 'integer',
			'parent_id' => 'integer'
		));

		// validate input
		$parameters = $this->validateParameters('get', array_merge(array('id' => $id),Input::all()));

		// if validation fails, return error
		if( isset($parameters['errors']) )
		{
			return Response::json(array('message' => 'Error while trying to retrieve records.', 'errors' => $parameters['errors']), 400);
		}

		// retrieve page
		if( $page = $this->content->getPage($parameters['id'], $parameters) )
		{
			return Response::json($page, 200);
		}

		// if page is not found
		return Response::json('Page not found.',404);

	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		// validation for get
		$this->parameters['get']['accepted'] = array(
			'id' =>'integer|required',
			'article_id' => 'integer',
			'menu_label' => '',
			'link' => 'alpha_dash',
			'status' => 'integer',
			'language' => 'alpha_dash',
			'data' => '',
			'tags' => ''
		);

		// validate input
		$parameters = $this->validateParameters('get', array_merge(array('id' => $id),Input::all()));

		// if validation fails, return error
		if( isset($parameters['errors']) )
		{
			return Response::json(array('message' => 'Error while trying to retrieve records.', 'errors' => $parameters['errors']), 400);
		}

		// get page
		$page = $this->content->getById($id);

		// restore if deleted
		$page->restore();

		// update all changed values
		foreach( $parameters as $key => $value )
		{
			$page->$key = $value;
		}

		//save model
		$page->save();

		return Response::json(array('message' => 'saved'), 200);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		// validation for get
		$this->parameters['get']['accepted'] = array(
			'id' =>'integer|required',
		);
		// validate input
		$parameters = $this->validateParameters('get', array('id' => $id) );

		// if validation fails, return error
		if( isset($parameters['errors']) )
		{
			return Response::json(array('message' => 'Error while trying to delete records.', 'errors' => $parameters['errors']), 400);
		}

		// get page data
		$content = $this->content->getById($id);

		// delete entry
		$this->content->delete($id);

		// delete stream entry
		$this->content->deleteStreamItem($content['article_id']);

		return Response::json(array('message' => 'Record deleted succesfully.'), 200);
	}


}
