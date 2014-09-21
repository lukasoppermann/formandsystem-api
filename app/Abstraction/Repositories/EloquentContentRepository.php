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
    if( !is_numeric( $id ) )
    {
      // get first page that matches link and language
      $data = $this->model->whereRaw('link = ? and language = ?',
                  array(str_replace($parameters['pathSeparator'],'/',$id), $parameters['language'])
              )->first();

      // if data['id'] is not set, return false
      if( !$data['id'] )
      {
        return false;
      }
      // set $id to data['id']
      $id = $data['id'];
    }

    // get page including deleted pages
    if( $parameters['withDeleted'] == "true" )
    {
      $page = $this->model->find($id);
    }
    // get page whitout deleted pages
    else
    {
      $page = $this->model->whereNull('deleted_at')->find($id);
    }

    // check if resulting page is valid
    if( isset($page->data) )
    {
      // decode json
      $page->data = $this->jsonDecode($page->data);
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
    // check if another page is connected to stream item
    if( $this->model->where('article_id', $article_id)->whereNull('deleted_at')->count() == 0)
    {
      // get streamItem that belongs to page
      $streamItem = $this->stream->where('article_id', $article_id)->first();
      // delete stream item
      $this->stream->delete($streamItem['id']);
      // retrun true
      return true;
    }
    return false;
  }

}
