<?php namespace Formandsystemapi\Repositories\Content;

use Formandsystemapi\Models\Content;

interface ContentRepositoryInterface
{

  public function getPage($id, $parameters);

  public function storePage($parameters);

  public function deletePage($id, $parameters);
}
