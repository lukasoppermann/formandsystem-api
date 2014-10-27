<?php namespace Formandsystemapi\Http\Controllers;

use Formandsystemapi\Http\respond;
use Formandsystemapi\Http\Requests\streams as Request;
use Formandsystemapi\Repositories\Content\ContentRepositoryInterface as ContentRepository;
use Formandsystemapi\Repositories\Stream\StreamRepositoryInterface as StreamRepository;
use Formandsystemapi\Transformers\StreamTransformer;
use Formandsystemapi\Transformers\PageTransformer;

class StreamsApiController extends BaseApiController {

  // protected $contentRepository;
  protected $streamRepository;
  protected $respond;
  protected $streamTransformer;
  protected $pageTransformer;

  /**
  * construct
  *
  * @return void
  */
  function __construct(ContentRepository $contentRepository, StreamRepository $streamRepository, StreamTransformer $streamTransformer, PageTransformer $pageTransformer, respond $respond)
  {
    // call parent constrcutor
    parent::__construct();

    // Repositories
    // $this->contentRepository = $contentRepository;
    $this->streamRepository = $streamRepository;

    // Transformer
    $this->streamTransformer = $streamTransformer;
    $this->pageTransformer = $pageTransformer;

    // respond
    $this->respond = $respond;
  }

  /**
   * Display a listing
   * @method index
   * @param  getStreamsRequest $request
   * @return Response
   */
  public function index(Request\getStreamsRequest $request)
  {
    // retrieve page
    if( $streams = $this->streamRepository->getStreamsArray() )
    {
      return $this->respond->ok( $streams );
    }

    // return 404 if no page exists
    return $this->respond->notFound();
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request\storeStreamRequest $request)
  {
    // get accepted fields
    $input = $this->streamTransformer->transformPostData(
        $request->only('parent_id', 'position', 'stream')
    );

    // store page
    if( $stream = $this->streamRepository->storeModel($input) )
    {
      return $this->respond->ok(['article_id' => $stream['article_id']]);
    }

    return $this->respond->internalError();
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($stream, Request\showStreamRequest $request)
  {

    // retrieve page
    if( $stream = $this->streamRepository->getArrayWhere( ['stream' => $stream] ) )
    {
      return $this->respond->ok($this->pageTransformer->transformArray($stream), 'pages#get');
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
  public function update($id, Request\updateStreamRequest $request)
  {

  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id, Request\deleteStreamRequest $request)
  {

  }

}
