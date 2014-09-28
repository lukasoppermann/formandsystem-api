<?php namespace Formandsystemapi\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Foundation\Http\FormRequest;
use Formandsystemapi\Repositories\Content\ContentRepositoryInterface as ContentRepository;
use Formandsystemapi\Http\Requests\BasicRequest;
use Formandsystemapi\Http\Requests\storePageRequest;
use Formandsystemapi\Http\Requests\showPageRequest;

class PagesApiController extends BaseApiController {

	protected $ContentRepository;

	/**
	* construct
	*
	* @return void
	*/
	function __construct(ContentRepository $ContentRepository)
	{
		// call parent constrcutor
		parent::__construct();

		// Repositories
		$this->ContentRepository = $ContentRepository;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return Response::json('Parameter missing: id or path. For more information read the documentation: http://dev.formandsystem.com',200);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(storePageRequest $request)
	{
		// if validation passes
		if( !$request->fails() )
		{

			$input = $request->only('stream', 'parent_id', 'position', 'article_id', 'link', 'status', 'language', 'data', 'tags');

			$page = $this->ContentRepository->storePage($input);

			// check if stored successfully
			if( isset($page['id']) )
			{
				return Response::json(array('success' => 'true', 'id' => $page['id'], 'article_id' => $page['article_id']), 200);
			}

		}

		// return errors
		$errors = array_merge(['storing' => 'Error while storing record.'], $request->messages());

		return Response::json(array('success' => 'false', 'errors' => $errors), 400);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id, showPageRequest $request)
	{
		return "yo";
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
