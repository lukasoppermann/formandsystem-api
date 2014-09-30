<?php namespace Formandsystemapi\Repositories\Stream;

interface StreamRepositoryInterface
{
  public function getByArticleId($article_id, $withTrashed);

  public function storeRecord($input);

  public function updateRecord($id, $input);

  public function deleteRecord($id);
}
