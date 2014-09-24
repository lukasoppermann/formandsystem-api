<?php

use Abstraction\Repositories\ContentRepositoryInterface as Content;
use Abstraction\Repositories\StreamRepositoryInterface as Stream;

class PagesapiController extends BaseApiController {

	// Repositories
	protected $content;

	/**
	* construct
	*
	* @return void
	*/
	function __construct(Content $content, Stream $stream)
	{
		// call parent constrcutor
		parent::__construct();

		// add page specific parameters
		$this->parameters['get']['default']['pathSeparator'] = '.';
		$this->parameters['get']['accepted']['pathSeparator'] = 'in:.,:,::';

		// Repositories
		$this->content = $content;
		$this->stream = $stream;
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
		// validation for post
		$this->parameters['post']['accepted'] = array(
			'stream' => 'alpha_dash|required_without:article_id',
			'parent_id' => 'integer|required',
			'position' => 'integer|required',
			'article_id' => 'integer|required_without:stream',
			'menu_label' => '',
			'link' => 'alpha_dash',
			'status' => 'integer|required',
			'language' => 'alpha_dash|required',
			'data' => '',
			'tags' => ''
		);
		// defaults for post
		$this->parameters['post']['default'] = array(
			'parent_id' => 0,
			'position' => 1,
			'menu_label' => '',
			'link' => '',
			'status' => 2,
			'language' => 'en',
			'data' => '',
			'tags' => '',
		);

		// get parameters
		$parameters = $this->validateParameters('post', Input::all());

		// if validation fails, return error
		if( isset($parameters['errors']) )
		{
			return Response::json(array('success' => 'false', 'errors' => $parameters['errors']), 400);
		}

		// create page
		$page = $this->content->storePage($parameters);

		// check if stored successfully
		if( isset($page['id']) )
		{
			return Response::json(array('success' => 'true', 'id' => $page['id'], 'article_id' => $page['article_id']), 200);
		}

		// error while storing
		return Response::json(array('success' => 'false', 'errors' => array('storing' => 'Error while storing record.')), 400);
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
			// return Response::make('darn', 400)->header('Content-Type', 'text/plain');

			return Response::json(array('success' => 'false', 'errors' => $parameters['errors']), 400);
		}

		// retrieve page
		if( $page = $this->content->getPage($parameters['id'], $parameters) )
		{
			return Response::json(array_merge(array('success' => 'true'), $page), 200);
		}

		// if page is not found
		return Response::json(array('success' => 'false', 'errors' => array('not_found' => 'Page not found')),404);

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
			return Response::json(array('success' => 'false', 'errors' => $parameters['errors']), 400);
		}

		// get page
		$page = $this->content->getById($id, true);

		// restore if deleted
		$page->restore();

		$this->stream->restoreByArticleId($page['article_id']);

		// update all changed values
		foreach( $parameters as $key => $value )
		{
			$page->$key = $value;
		}

		//save model
		$page->save();

		return Response::json(array('success' => 'true'), 200);
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
			return Response::json(array('success' => 'false', 'errors' => $parameters['errors']), 400);
		}

		// get page data
		$content = $this->content->getById($id);

		// delete entry
		$success = $this->content->delete($id);

		// delete stream entry
		$this->content->deleteStreamItem($content['article_id']);

		return Response::json(array('success' => $success), 200);
	}


}
