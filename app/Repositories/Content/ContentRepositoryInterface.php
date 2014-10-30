<?php namespace Formandsystemapi\Repositories\Content;

interface ContentRepositoryInterface
{
  public function getArrayByLinkOrId($link, $language, $withTrashed);

  public function getArrayById($id, $withTrashed);

  public function getArrayWhere($whereArray, $withTrashed);

  public function storeModel($input);

  public function updateModel($id, $input);


}
