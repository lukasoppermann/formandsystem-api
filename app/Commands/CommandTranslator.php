<?php namespace Formandsystemapi\Commands;

use Exception;

/**
 * CommandTranslator
 */
class CommandTranslator
{
  /**
   * translate to command handler
   *
   * @method toCommandHandler
   *
   * @param  object $command
   */
  public function toCommandHandler($command)
  {
    $handler = str_replace('Command', 'CommandHandler', get_class($command));

    if( ! class_exists($handler) )
    {
      $message = "Command handler [$handler] does not exist.";

      throw new Exception($message);
    }

    return $handler;
  }
}
