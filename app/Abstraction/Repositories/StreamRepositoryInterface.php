<?php namespace Abstraction\Repositories;

interface StreamRepositoryInterface {

  // returns items from a stream
  public function getStream($stream, $parameters);

  // returns first item from a stream
  public function getFirst($stream);

}
