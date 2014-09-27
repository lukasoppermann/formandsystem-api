<?php namespace Formandsystemapi\Repositories;

interface AbstractRepositoryInterface
{
  public function getAll(array $with);

  public function getById($id, $withTrashed);

  public function delete($id);
}
