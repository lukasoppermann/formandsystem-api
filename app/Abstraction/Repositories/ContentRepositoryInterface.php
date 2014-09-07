<?php namespace Abstraction\Repositories;

interface ContentRepositoryInterface {

  // returns a page
  public function getPage($id, $parameters);

}
