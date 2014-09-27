<?php namespace Formandsystemapi\Repositories\Stream;

use Formandsystemapi\Models\Stream;
use Formandsystemapi\Repositories\EloquentAbstractRepository;

class EloquentStreamRepository extends EloquentAbstractRepository implements StreamRepositoryInterface
{
  protected $model;
  /**
  * Constructor
  */
  public function __construct(Stream $model)
  {
    $this->model = $model;
  }

}
