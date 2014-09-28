<?php namespace Formandsystemapi\Repositories\User;

use Formandsystemapi\Models\User;
use Formandsystemapi\Repositories\EloquentAbstractRepository;

class EloquentUserRepository extends EloquentAbstractRepository implements UserRepositoryInterface
{
  protected $model;
  /**
  * Constructor
  */
  public function __construct(User $model)
  {
    $this->model = $model;
  }

  /**
   * Get by owner id
   *
   * @return collection
   */
  public function getByOwnerId($owner_id)
  {
    return $this->model->where('owner_id',$owner_id)->first();
  }
}
