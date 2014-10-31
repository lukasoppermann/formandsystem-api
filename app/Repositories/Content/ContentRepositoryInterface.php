<?php namespace Formandsystemapi\Repositories\Content;

interface ContentRepositoryInterface
{
  public function getArrayById($id, $withTrashed);

  public function getArrayWhere($whereArray, $withTrashed);

  public function storeModel($input);

}
