<?php namespace Formandsystemapi\Repositories\Content;

interface ContentRepositoryInterface
{
  public function getPageByLink($link, $language, $withTrashed);

  public function getPageById($id, $withTrashed);

  public function storePage($parameters);

  public function deletePage($id, $parameters);
}
