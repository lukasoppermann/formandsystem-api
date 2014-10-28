<?php namespace Formandsystemapi\Repositories\Stream;

interface StreamRepositoryInterface
{
  public function getByArticleId($article_id);

  public function storeModel($input);

  public function updateModel($id, $input);

  public function deleteModel($id);
}
