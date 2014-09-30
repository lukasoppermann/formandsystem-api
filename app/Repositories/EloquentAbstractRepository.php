<?php namespace Formandsystemapi\Repositories;

abstract class EloquentAbstractRepository implements AbstractRepositoryInterface
{

  /**
  * @var Model
  */
  protected $model;

  /**
   * Include trashed records in query
   *
   * @param array $with
   * @return Illuminate\Database\Eloquent\Collection
   */
  public function withTrashed($withTrashed = false)
  {
    if( $withTrashed == true )
    {
      return $this->model->withTrashed();
    }
    return $this->model;
  }

  /**
   * Return entry by id optionally with additional data
   *
   * @return Illuminate\Database\Eloquent\Collection
   */
  public function getById($id, $withTrashed = false)
  {
    $query = $this->model;

    if( $withTrashed === true )
    {
      $query = $query->withTrashed();
    }

    return $query->find($id);
  }

  /**
  * delete a database entry
  *
  * @return boolean
  */
  public function delete($id)
  {
    if( $record = $this->model->find($id) )
    {
      return $record->delete();
    }

    return false;
  }

}
