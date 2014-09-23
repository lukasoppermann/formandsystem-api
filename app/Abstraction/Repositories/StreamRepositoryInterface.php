<?php namespace Abstraction\Repositories;

interface StreamRepositoryInterface {

  // returns items from a stream
  public function getStream($stream, $parameters);

  // returns first item from a stream
  public function getFirst($stream);

  // store an item to a stream
  public function storeStreamItem($parameters);

  // get an item by article_od
  public function getByArticleId($article_id, $withTrashed);

  // delete an item by article_od
  public function deleteByArticleId($article_id);

  // restore an item by article_od
  public function restoreByArticleId($article_id);

}
