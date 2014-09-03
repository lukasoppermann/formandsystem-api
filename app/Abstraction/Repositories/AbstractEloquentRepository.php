<?php namespace Abstraction\Repositories;

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
  public function getById($id, array $with = array())
  {
    $query = $this->make($with);

    return $query->find($id);
  }

  //
  // public function delete($id);

  //
  // public function create($input);
  //
  // public function update($id, $input);

}
