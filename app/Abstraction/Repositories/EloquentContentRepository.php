<?php namespace Abstraction\Repositories;

use Content;

class EloquentContentRepository extends AbstractEloquentRepository implements ContentRepositoryInterface {

  /**
  * Constructor
  */
  public function __construct(Content $model)
  {
    $this->model = $model;
  }

  /**
   * GET single page
   *
   * @return array
   */
  function getPage( $id, $parameters )
  {
    if( !is_numeric( $id ) && $data = $this->model->whereRaw('link = ? and language = ?', array($id, $parameters['language']) )->first() )
    {
      $id = $data['id'];
    }
    // get page
    $page = $this->model->find($id);
    // decode json
    $page->data = $this->jsonDecode($page->data);
    // return Item
    return $page->toArray();
  }

}
