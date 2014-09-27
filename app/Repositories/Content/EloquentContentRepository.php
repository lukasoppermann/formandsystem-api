<?php namespace Formandsystemapi\Repositories\Content;

use Formandsystemapi\Models\Content;
use Formandsystemapi\Repositories\EloquentAbstractRepository;

class EloquentContentRepository extends EloquentAbstractRepository implements ContentRepositoryInterface
{

  protected $model;
  /**
  * Constructor
  */
  public function __construct(Content $model)
  {
    $this->model = $model;
  }

  public function getPage($id, $parameters)
  {

  }

  public function storePage($parameters)
  {

  }

  public function deletePage($id, $parameters)
  {

  }
}
