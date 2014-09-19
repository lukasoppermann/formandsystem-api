<?php namespace Abstraction\Repositories;

interface AbstractRepositoryInterface {

	public function make(array $with);

  public function getAll(array $with);

  public function getById($id, array $with);

	public function delete($id);

}
