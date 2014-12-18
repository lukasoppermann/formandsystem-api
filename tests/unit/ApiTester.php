<?php

use Faker\Factory as Faker;

class ApiTester extends TestCase {

  protected $fake;
  protected $times = 1;

  function __construct()
  {
    $this->fake = Faker::create();
  }

  /**
   * set times variable
   *
   * @method times
   *
   * @param  int $count
   *
   * @return
   */
  protected function times($count)
  {
    $this->times = $count;
  }

  /**
   * create a table in db
   *
   * @method make
   *
   * @param  string $type
   * @param  array $fields
   *
   * @return
   */
  protected function make($type, $fields = [])
  {
    while($this->times--)
    {
      $stub = array_merge($this->getStub(), $fields);

      $type::create($stub);
    }
  }

  /**
   * @throws BadMethodCallException
   *
   * @method getStub
   */
  protected function getStub()
  {
    throw new BadMethodCallException('Create your own getStub method to declare your fields.');
  }


}
