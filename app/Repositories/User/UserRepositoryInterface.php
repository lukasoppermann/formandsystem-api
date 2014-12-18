<?php namespace Formandsystemapi\Repositories\User;

interface UserRepositoryInterface
{
  public function getByOwnerId($owner_id);

}
