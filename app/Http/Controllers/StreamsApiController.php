<?php namespace Formandsystemapi\Http\Controllers;

use Formandsystemapi\Http\respond;
use Formandsystemapi\Http\Requests\streams as Request;
use Formandsystemapi\Repositories\Content\ContentRepositoryInterface as ContentRepository;
use Formandsystemapi\Repositories\Stream\StreamRepositoryInterface as StreamRepository;
use Formandsystemapi\Transformers\StreamTransformer;

class StreamsApiController extends BaseApiController {

  // protected $contentRepository;
  protected $streamRepository;
  protected $respond;
  protected $streamTransformer;

  /**
  * construct
  *
  * @return void
  */
  function __construct(ContentRepository $contentRepository, StreamRepository $streamRepository, StreamTransformer $streamTransformer, respond $respond)
  {
    // call parent constrcutor
    parent::__construct();

    // Repositories
    // $this->contentRepository = $contentRepository;
    $this->streamRepository = $streamRepository;

    // Transformer
    $this->streamTransformer = $streamTransformer;

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

  

}
