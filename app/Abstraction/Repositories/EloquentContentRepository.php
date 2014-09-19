<?php namespace Abstraction\Repositories;

use Content;
use Stream;

class EloquentContentRepository extends AbstractEloquentRepository implements ContentRepositoryInterface {

  /**
  * Constructor
  */
  public function __construct(Content $model, Stream $stream)
  {
    $this->model = $model;
    $this->stream = $stream;
  }

  /**
   * GET single page
   *
   * @return array
   */
  function getPage( $id, $parameters )
  {

    // get id if path is given
    if( !is_int( $id ) )
    {
      $data = $this->model->whereRaw('link = ? and language = ?', array($id, $parameters['language']) )->first();
      if( $data['id'] )
      {
        $id = $data['id'];
      }
      else
      {
        return false;
      }
    }
    // get page
    if( $parameters['withDeleted'] == "true" )
    {
      $page = $this->model->find($id);
    }
    else
    {
      $page = $this->model->find($id)->whereNull('deleted_at');
    }

    if( isset($page['data']) )
    {
      // decode json
      $page['data'] = $this->jsonDecode($page['data']);
      // return Item
      return $page->toArray();
    }
    // missing id or wrong id or delete item
    return false;
  }

  /**
   * Deletes stream item if no entry is connected
   *
   * @return array
   */
  function deleteStreamItem( $article_id )
  {
    if( $this->model->where('article_id', $article_id)->whereNull('deleted_at')->count() == 0)
    {
      $streamItem = $this->stream->where('article_id', $article_id)->first();
      $this->stream->delete($streamItem['id']);
    }
  }

}
