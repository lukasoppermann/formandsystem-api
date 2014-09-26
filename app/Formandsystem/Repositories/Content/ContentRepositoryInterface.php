<?php namespace Formandsystem\Repositories\Content;

interface ContentRepositoryInterface {

  // returns a page
  public function getPage($id, $parameters);

  // creates a page
  public function storePage($parameters);

  // deletes a stream item if no other pages are connected
  public function deleteStreamItem($article_id);

}
