<?php namespace Formandsystemapi\Repositories\Settings;

interface SettingsRepositoryInterface
{
  public function getAll();

  public function getByGroup( $group );

  // public function storeModel($input);
}
