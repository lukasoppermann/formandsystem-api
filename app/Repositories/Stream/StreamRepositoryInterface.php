<?php namespace Formandsystemapi\Repositories\Stream;

interface StreamRepositoryInterface
{
  public function storeModel($input);

  public function updateModel($id, $input);
}
