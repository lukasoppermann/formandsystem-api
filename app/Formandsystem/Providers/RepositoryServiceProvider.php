<?php namespace Formandsystem\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider {

  public function register()
  {
    $this->app->bind(
      'Formandsystem\Repositories\Content\ContentRepositoryInterface',
      'Formandsystem\Repositories\Content\EloquentContentRepository'
    );
    $this->app->bind(
      'Formandsystem\Repositories\Stream\StreamRepositoryInterface',
      'Formandsystem\Repositories\Stream\EloquentStreamRepository'
    );
  }

}
