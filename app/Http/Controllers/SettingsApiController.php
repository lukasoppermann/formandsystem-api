<?php namespace Formandsystemapi\Http\Controllers;

use Formandsystemapi\Http\respond;
use Formandsystemapi\Http\Requests\Settings as Request;
use Formandsystemapi\Transformers\SettingsTransformer;
use Formandsystemapi\Repositories\Settings\SettingsRepositoryInterface as SettingsRepository;

class SettingsApiController extends BaseApiController {

  protected $settingsRepository;
  protected $settingsTransformer;
  protected $respond;

  /**
  * construct
  *
  * @return void
  */
  function __construct(SettingsRepository $settingsRepository, SettingsTransformer $settingsTransformer, respond $respond)
  {
    // call parent constrcutor
    parent::__construct();

    // Repositories
    $this->settingsRepository = $settingsRepository;

    // Transformer
    $this->settingsTransformer = $settingsTransformer;

    // respond
    $this->respond = $respond;
  }

  /**
   * Display a listing
   * @method index
   * @param  getStreamsRequest $request
   * @return Response
   */
  public function index(Request\getSettingsRequest $request)
  {
    // retrieve page
    if( $settings = $this->settingsRepository->getAll() )
    {

      foreach($settings as $key => $group)
      {
        $settings[$key] = $this->settingsTransformer->transformArray($group);
      }

      return $this->respond->ok( $settings );
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
    // $input = $this->streamTransformer->transformPostData(
    //     $request->only('parent_id', 'position', 'stream')
    // );
    //
    // // store page
    // if( $stream = $this->streamRepository->storeModel($input) )
    // {
    //   return $this->respond->ok(['article_id' => $stream['article_id']]);
    // }
    //
    // return $this->respond->internalError();
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $group
   * @return Response
   */
  public function show($group, Request\showSettingsRequest $request)
  {
    // retrieve page
    if( $settings = $this->settingsRepository->getByGroup( $group ) )
    {
      return $this->respond->ok( $this->settingsTransformer->transformArray( $settings ) );
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
    // $input = array_filter($request->only('stream','parent_id','position'));
    //
    // if( count($input) == 0 )
    // {
    //   return $this->respond->badRequest();
    // }
    //
    // // update model with input & restore if deleted
    // if( $this->streamRepository->update($article_id, $input) )
    // {
    //   return $this->respond->noContent();
    // }
    //
    // return $this->respond->notFound();
  }

}
