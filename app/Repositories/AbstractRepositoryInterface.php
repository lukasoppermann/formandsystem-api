<?php namespace Formandsystemapi\Repositories;

interface AbstractRepositoryInterface
{
  public function getById($id, $withTrashed);

  public function delete($id);
}
