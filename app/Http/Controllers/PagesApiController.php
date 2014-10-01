<?php namespace Formandsystemapi\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Foundation\Http\FormRequest;
use Formandsystemapi\Repositories\Content\ContentRepositoryInterface as ContentRepository;
use Formandsystemapi\Repositories\Stream\StreamRepositoryInterface as StreamRepository;
use Formandsystemapi\Http\Requests\BasicRequest;
use Formandsystemapi\Http\Requests\getPagesRequest;
use Formandsystemapi\Http\Requests\storePageRequest;
use Formandsystemapi\Http\Requests\showPageRequest;
use Formandsystemapi\Http\Requests\updatePageRequest;
use Formandsystemapi\Http\Requests\deletePageRequest;

class PagesApiController extends BaseApiController {

	protected $contentRepository;
	protected $streamRepository;

	/**
	* construct
	*
	* @return void
	*/
	function __construct(ContentRepository $contentRepository, StreamRepository $streamRepository)
	{
		// call parent constrcutor
		parent::__construct();

		// Repositories
		$this->contentRepository 	= $contentRepository;
		$this->streamRepository 	= $streamRepository;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(getPagesRequest $request)
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
			// get accepted fields
			$input = $request->only('stream', 'parent_id', 'position', 'article_id', 'link', 'status', 'language', 'data', 'tags');

			// create stream record and get article_id if needed
			if( !isset($input['article_id']) )
			{
				$input['article_id'] = $this->streamRepository->storeModel([
					'stream' => $input['stream'],
					'parent_id' => $input['parent_id'],
					'position' => $input['position']
				]);
			}

			// store page
			if( $page = $this->contentRepository->storeModel($input) )
			{
				return Response::json(array('success' => 'true', 'id' => $page['id'], 'article_id' => $page['article_id']), 200);
			}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id, showPageRequest $request)
	{
		$parameters = array_merge(
										array('status' => 1,'language' => 'en', 'pathSeparator' => '.'),
										array_filter($request->only('status','language','pathSeparator'))
		);

		// retrieve page
		if( $page = $this->contentRepository->getArrayByLink( str_replace($parameters['pathSeparator'],'/',$id), $parameters['language'] ) )
		{
			return Response::json(array_merge(array('success' => 'true'), $page), 200);
		}

		return Response::json(array_merge(array('success' => 'true'), ['not_found' => 'Page not found']), 200);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, updatePageRequest $request)
	{
		// if validation passes
		if( !$request->messages() )
		{
			$input = $request->only('status','language','article_id','data','tags','menu_label','link');

			$page = $this->contentRepository->updateModel($id, $input);

			$this->streamRepository->updateModel($page['stream_record_id']);

			return Response::json(array('success' => 'true'), 200);
		}

		// return errors
		$errors = array_merge(['updating' => 'Error while updating record.'], $request->messages());

		return Response::json(array('success' => 'false', 'errors' => $errors), 400);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id, deletePageRequest $request)
	{
		// if validation passes
		if( !$request->messages() )
		{
			$page = $this->contentRepository->deleteModel($id);

			// check if no other item is connected to the stream record
			if( count($this->contentRepository->getArrayWhere(['article_id' => $page['article_id']], false)) == 0)
    	{
				$this->streamRepository->deleteModel($page['stream_record_id']);
			}

			return Response::json(array('success' => 'true'), 200);
		}

		// return errors
		$errors = array_merge(['deleting' => 'Error while deleting record.'], $request->messages());

		return Response::json(array('success' => 'false', 'errors' => $errors), 400);
	}
}
