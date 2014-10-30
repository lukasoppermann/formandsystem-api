<?php namespace Formandsystemapi\Commands;

interface CommandHandlerInterface {

  /**
   * handle the command
   *
   * @method handle
   *
   * @param  object $command
   *
   * @return mixed
   */
  public function handle($commands);
}
