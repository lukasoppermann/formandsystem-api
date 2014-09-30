<?php namespace Formandsystemapi\Repositories\Stream;

interface StreamRepositoryInterface
{
  public function getByArticleId($article_id, $withTrashed);

  public function storeRecord($parameters);

  public function updateRecord($id, $parameters);

  public function deleteRecord($id);
}
