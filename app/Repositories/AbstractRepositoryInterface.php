<?php namespace Formandsystemapi\Repositories;

interface AbstractRepositoryInterface
{
  public function limit($limit);

  public function offset($offset);

  public function withTrashed($withTrashed);

  public function getById($id, $withTrashed);

  public function queryWhere($whereArray, $withTrashed);

  public function delete($id, $force);

  public function update($id, $input);
}
