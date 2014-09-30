<?php namespace Formandsystemapi\Repositories\Content;

interface ContentRepositoryInterface
{
  public function getPageByLink($link, $language, $withTrashed);

  public function getPageById($id, $withTrashed);

  public function storePage($input);

  public function updatePage($id, $input);

  public function deletePage($id);
}
