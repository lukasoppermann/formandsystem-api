<?php namespace Formandsystemapi\Commands;

use Illuminate\Foundation\Application;

/**
 * commandBus
 */
class CommandBus
{

  private $app;

  protected $commandTranslator;

  function __construct(Application $app, CommandTranslator $commandTranslator)
  {
    $this->app = $app;
    $this->commandTranslator = $commandTranslator;
  }

  
  public function execute($command)
  {
    $handler = $this->commandTranslator->toCommandHandler($command);

    // resolve out of ioc and call handle method
    return $this->app->make($handler)->handle($command);
  }

}
