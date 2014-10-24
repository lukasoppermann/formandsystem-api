<?php namespace Formandsystemapi\Http\Controllers;

use Illuminate\Foundation\Http\FormRequest;
use Formandsystemapi\Repositories\Content\ContentRepositoryInterface as ContentRepository;
use Formandsystemapi\Repositories\Stream\StreamRepositoryInterface as StreamRepository;
use Formandsystemapi\Http\Requests\BasicRequest;
use Formandsystemapi\Http\Requests\getPagesRequest;
use Formandsystemapi\Http\Requests\storePageRequest;
use Formandsystemapi\Http\Requests\showPageRequest;
use Formandsystemapi\Http\Requests\updatePageRequest;
use Formandsystemapi\Http\Requests\deletePageRequest;
use Formandsystemapi\Transformers\PageTransformer;
use Formandsystemapi\Http\respond;

class PagesApiController extends BaseApiController {

	protected $contentRepository;
	protected $streamRepository;
	protected $pageTransformer;
	protected $respond;

	/**
	* construct
	*
	* @return void
	*/
	function __construct(ContentRepository $contentRepository, StreamRepository $streamRepository, PageTransformer $pageTransformer, respond $respond)
	{
		// call parent constrcutor
		parent::__construct();

		// Repositories
		$this->contentRepository = $contentRepository;
		$this->streamRepository = $streamRepository;

		// Transformer
		$this->pageTransformer = $pageTransformer;

		// respond
		$this->respond = $respond;
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
			return $this->respond->ok( $this->pageTransformer->transformArray($page) );
		}

		// return 404 if no page exists
		return $this->respond->notFound();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(storePageRequest $request)
	{
			// get accepted fields
			$input = $this->pageTransformer->transformPostData(
					$request->only('parent_id', 'menu_label', 'position', 'article_id', 'link', 'status', 'language', 'data', 'tags')
			);

			// store page
			if( $page = $this->contentRepository->storeModel($input) )
			{
				return $this->respond->ok(['id' => $page['id'], 'article_id' => $page['article_id']]);
			}

			return $this->respond->internalError();
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
			return $this->respond->ok($this->pageTransformer->transform($page), 'pages#get');
		}

		// return 404 if no page exists
		return $this->respond->notFound();
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
		$input = $this->pageTransformer->transformPostData( $request->only('status','language','article_id','data','tags','menu_label','link') );

		// update model with input & restore if deleted
		if( $this->contentRepository->updateModel($id, $input) )
		{
			return $this->respond->noContent();
		}

		return $this->respond->notFound();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id, deletePageRequest $request)
	{
		if( $this->contentRepository->deleteModel($id) )
		{
			return $this->respond->noContent();
		}

		return $this->respond->notFound();

	}

}
