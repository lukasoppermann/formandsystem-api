<?php namespace Formandsystemapi\Repositories;

abstract class EloquentAbstractRepository implements AbstractRepositoryInterface
{

  /**
  * @var Model
  */
  protected $model;
  protected $limit;
  protected $offset;

  /**
   * set limit to a certain amount
   *
   * @param integer $limit
   *
   * @return class instance $this
   */
  public function limit($limit = 20)
  {
    if( is_int((int) $limit) && (int) $limit > 0 )
    {
      $this->limit = $limit;
    }

    return $this;
  }

  /**
   * set offset value
   *
   * @param integer $offset
   *
   * @return class instance $this
   */
  public function offset($offset = 0)
  {
    if( is_int((int) $offset))
    {
      $this->offset = $offset;
    }

    return $this;
  }


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
   * add wheres to query
   *
   * @return array
   */
  public function queryWhere($whereArray = [], $withTrashed = false)
  {
    $query = $this->withTrashed($withTrashed);

    foreach ($whereArray as $key => $value)
    {
      $operator = "=";

      if( is_array($value) )
      {
        $operator = $value[0];
        $value = $value[1];
      }

      $query = $query->where($key, $operator, $value);
    }
    return $query;
  }

  /**
   * Return entry by id optionally with additional data
   *
   * @return Illuminate\Database\Eloquent\Collection
   */
  public function getById($id, $withTrashed = false)
  {
    return $this->withTrashed($withTrashed)->find($id);
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
