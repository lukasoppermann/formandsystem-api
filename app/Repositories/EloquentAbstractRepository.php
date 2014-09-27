<?php namespace Formandsystemapi\Repositories;

abstract class EloquentAbstractRepository implements AbstractRepositoryInterface
{

  /**
  * @var Model
  */
  protected $model;

  /**
   * Make a new instance of the entity to query on with the given parameters
   *
   * @param array $with
   * @return Illuminate\Database\Eloquent\Collection
   */
  public function makeWith(array $with = array())
  {
    return $this->model->with($with);
  }

  /**
   * Return all entries
   *
   * @return Illuminate\Database\Eloquent\Collection
   */
  public function getAll(array $with = array())
  {
    $query = $this->makeWith($with);

    return $query->get();
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
