<?php namespace Abstraction\Repositories;

use \DateTime;

abstract class AbstractEloquentRepository implements AbstractRepositoryInterface{

  /**
   * @var Model
   */
  protected $model;

  /**
   * Make a new instance of the entity to query on
   *
   * @param array $with
   */
  public function make(array $with = array())
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
    $query = $this->make($with);

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
    if($record = $this->model->find($id))
    {
      return $record->delete();
    }
    return false;
  }

  /**
   * Convert json
   *
   * @return object / array
   */
  public function jsonDecode($value)
  {
    if( is_string($value) )
    {
      $data = json_decode($value, true);
      if( is_object($data) || is_array($data) )
      {
        return $data;
      }
    }
    return $value;
  }


  /**
  * Validate a date to be YYYY-MM-DD
  *
  * @return boolean
  */
  function _validateDate($date)
  {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') == $date;
  }

  //
  // public function create($input);
  //
  // public function update($id, $input);

}
