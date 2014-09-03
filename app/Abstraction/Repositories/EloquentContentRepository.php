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

  public function getContent( $param )
  {
    return $this->getAll(['navigation']);
  }

  public function getPage( $id )
  {
    return $this->model->getPage($id);
    return $this->getById($id);
  }

}
