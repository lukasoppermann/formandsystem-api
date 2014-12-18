<?php namespace Formandsystemapi\Repositories\Content;

interface ContentRepositoryInterface
{

  public function getArticleId($idOrLink, $language);

  public function storeModel($input);

}
