<?php namespace Formandsystemapi\Repositories;

interface AbstractRepositoryInterface
{

  public function delete($id, $force);

  public function update($id, $input);
}
