<?php namespace Abstraction\Repositories;

interface ContentRepositoryInterface {

  // returns a page
  public function getPage($id, $parameters);

  // deletes a stream item if no other pages are connected
  public function deleteStreamItem($article_id);

}
