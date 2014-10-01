<?php namespace Formandsystemapi\Repositories\Content;

interface ContentRepositoryInterface
{
  public function getArrayByLink($link, $language, $withTrashed);

  public function getArrayById($id, $withTrashed);

  public function storeModel($input);

  public function updateModel($id, $input);

  public function deleteModel($id);
}
