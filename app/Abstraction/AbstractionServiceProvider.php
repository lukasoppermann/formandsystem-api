<?php namespace Abstraction;

use Illuminate\Support\ServiceProvider;

class AbstractionServiceProvider extends ServiceProvider {

  public function register()
  {
    $this->app->bind(
      'Abstraction\Repositories\ContentRepositoryInterface',
      'Abstraction\Repositories\EloquentContentRepository'
    );
    $this->app->bind(
      'Abstraction\Repositories\StreamRepositoryInterface',
      'Abstraction\Repositories\EloquentStreamRepository'
    );
  }

}
