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
	 * Display a listing
	 * @method index
	 * @param  getPagesRequest $request
	 * @return Response
	 */
	public function index(getPagesRequest $request)
	{
		// get accepted fields
		$input = $request->only('parent_id', 'menu_label', 'position', 'article_id', 'link', 'status', 'language', 'data', 'tags');
		
		// retrieve page
		if( $page = $this->contentRepository->getArrayWhere( array_filter($input) ) )
		{
			return $this->respondOk($page);
		}

		// return 404 if no page exists
		return $this->respondNotFound();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(storePageRequest $request)
	{
			// get accepted fields
			$input = $request->only('parent_id', 'menu_label', 'position', 'article_id', 'link', 'status', 'language', 'data', 'tags');

			// store page
			if( $page = $this->contentRepository->storeModel($input) )
			{
				return $this->respondOk(['id' => $page['id'], 'article_id' => $page['article_id']]);
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
		// merge parameters with defaults
		$parameters = array_merge(
										array('status' => 1,'language' => 'en', 'pathSeparator' => '.'),
										array_filter($request->only('status','language','pathSeparator'))
		);

		// retrieve page
		if( $page = $this->contentRepository->getArrayByLink( str_replace($parameters['pathSeparator'],'/',$id), $parameters['language'] ) )
		{
			return $this->respondOk($page);
		}

		// return 404 if no page exists
		return $this->respondNotFound();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, updatePageRequest $request)
	{
		// get input
		$input = $request->only('status','language','article_id','data','tags','menu_label','link');

		// update model with input & restore if deleted
		$page = $this->contentRepository->updateModel($id, $input);

		// // update stream just to restore if deleted
		// TODO: should this be done in the api?
		// $this->streamRepository->updateModel($page['stream_record_id']);

		return $this->respondNoContent();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id, deletePageRequest $request)
	{
		$page = $this->contentRepository->deleteModel($id);

		// check if no other item is connected to the stream record
		// TODO: should this be done in the api?
		// if( count($this->contentRepository->getArrayWhere(['article_id' => $page['article_id']], false)) == 0)
  	// {
		// 	$this->streamRepository->deleteModel($page['stream_record_id']);
		// }

		return $this->respondNoContent();
	}

}
