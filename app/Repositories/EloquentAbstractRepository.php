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
   * @return $this
   */
  public function modelWithTrashed(&$withTrashed = false)
  {
    if($withTrashed === true)
    {
      return $this->model->withTrashed();
    }

    return $this->model;
  }

  /**
  * delete a database entry
  *
  * @return boolean
  */
  public function delete($id, $force = false)
  {
    if( $record = $this->model->withTrashed()->find($id) )
    {
      if($force === true)
      {
        return $record->forceDelete();
      }

      return $record->delete();
    }

    return false;
  }

  /**
   * Update the specified record
   *
   * @param  int  $id
   * @return array | bool
   */
   public function update($id, $input = [])
   {
      if( $record = $this->model->withTrashed()->find($id) )
      {
        // restore if deleted
        $record->restore();

        // update all changed values
        $record->update($input);

        return true;
      }

      return false;
   }

}
