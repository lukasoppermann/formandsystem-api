<?php namespace Formandsystemapi\Repositories\Stream;

interface StreamRepositoryInterface
{
  public function getWhere($whereArray);

  public function storeModel($input);
}
