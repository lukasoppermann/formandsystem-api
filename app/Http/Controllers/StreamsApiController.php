<?php namespace Formandsystemapi\Http\Controllers;

use Formandsystemapi\Http\respond;
use Formandsystemapi\Http\Requests\Streams as Request;
use Formandsystemapi\Repositories\Stream\StreamRepositoryInterface as StreamRepository;
use Formandsystemapi\Transformers\StreamTransformer;
use Formandsystemapi\Transformers\PageTransformer;
use Formandsystemapi\Services\NestArrayService;

class StreamsApiController extends BaseApiController {

  protected $streamRepository;
  protected $respond;
  protected $streamTransformer;
  protected $pageTransformer;

  /**
  * construct
  *
  * @return void
  */
  function __construct(StreamRepository $streamRepository, StreamTransformer $streamTransformer, PageTransformer $pageTransformer, respond $respond)
  {
    // call parent constrcutor
    parent::__construct();

    // Repositories
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
      return $this->respond->ok( $this->streamTransformer->transform($streams) );
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
  public function show($stream, Request\showStreamRequest $request, NestArrayService $nestArrayService)
  {
    // get input
    $parameters = array_filter($request->only('limit','offset','from','until','withtrashed', 'language', 'withdrafts'));

    // retrieve page
    if( $stream = $this->streamRepository->getWhere( ['stream' => $stream], $parameters ) )
    {
      return $this->respond->ok(
                $nestArrayService->nest( $this->pageTransformer->transformArray($stream) )
            ,'pages#get');
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
  public function update($article_id, Request\updateStreamRequest $request)
  {
    // get input
    $input = array_filter($request->only('stream','parent_id','position'));

    if( count($input) == 0 )
    {
      return $this->respond->badRequest();
    }

    // update model with input & restore if deleted
    if( $this->streamRepository->update($article_id, $input) )
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
  public function destroy($stream_record_id, Request\deleteStreamRequest $request)
  {
    if( $this->streamRepository->delete($stream_record_id) )
    {
      return $this->respond->noContent();
    }

    return $this->respond->notFound();
  }


}
