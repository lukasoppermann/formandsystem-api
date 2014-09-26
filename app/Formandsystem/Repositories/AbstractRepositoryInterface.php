<?php namespace Formandsystem\Repositories;

interface AbstractRepositoryInterface {

	public function make(array $with);

  public function getAll(array $with);

  public function getById($id, $withTrashed);

	public function delete($id);

}
