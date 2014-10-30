<?php namespace Formandsystemapi\Repositories;

interface AbstractRepositoryInterface
{
  public function limit($limit);

  public function offset($offset);

  public function getById($id, $withTrashed);

  public function delete($id);

  public function queryWhere($whereArray, $withTrashed);
}
