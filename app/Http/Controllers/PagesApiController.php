<?php namespace Formandsystemapi\Http\Controllers;

use Illuminate\Foundation\Http\FormRequest;
use Formandsystemapi\Repositories\Content\ContentRepositoryInterface as ContentRepository;
use Formandsystemapi\Repositories\Stream\StreamRepositoryInterface as StreamRepository;
use Formandsystemapi\Http\Requests\Pages as Request;
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
	public function index(Request\getPagesRequest $request)
	{
		// get accepted fields, filter everything but 0
		$input = array_filter($request->only('parent_id'), function($var){
			return ($var !== NULL && $var !== FALSE && $var !== '');
		});

		$parameters = $request->only('language', 'limit', 'offset');

		// retrieve page
		if( $pages = $this->streamRepository->getWhere( $input, array_filter($parameters) ) )
		{
			return $this->respond->ok( $this->pageTransformer->transformArray($pages) );
		}

		// return 404 if no page exists
		return $this->respond->notFound();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request\storePageRequest $request)
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
	public function show($idOrLink, Request\showPageRequest $request)
	{
		// merge parameters with defaults
		$parameters = array_merge(
										array('status' => 1, 'pathSeparator' => '.'),
										array_filter($request->only('status','language','pathSeparator'))
		);

		$article_id = $this->contentRepository->getArticleId( str_replace($parameters['pathSeparator'],'/',$idOrLink), $parameters['language'] );

		$page = $this->streamRepository->getWhere(
			['article_id' => $article_id],
			$parameters
		);
		// return page in 200 response
		if( $page )
		{
			return $this->respond->ok($this->pageTransformer->transform($page[0]), 'pages#get');
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
	public function update($id, Request\updatePageRequest $request)
	{
		// get input
		$input = $this->pageTransformer->transformPostData( $request->only('status','language','article_id','data','tags','menu_label','link') );

		// update model with input & restore if deleted
		if( $this->contentRepository->update($id, $input) )
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
	public function destroy($id, Request\deletePageRequest $request)
	{
		if( $this->contentRepository->delete($id) )
		{
			return $this->respond->noContent();
		}

		return $this->respond->notFound();

	}

}
