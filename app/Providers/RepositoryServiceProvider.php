<?php namespace Formandsystemapi\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider {

  /**
   * Bootstrap any necessary services.
   *
   * @return void
   */
  public function boot()
  {
    //
  }

  /**
   * Register the service provider.
   *
   * @return void
   */
  public function register()
  {
    $this->app->bind(
      'Formandsystemapi\Repositories\Content\ContentRepositoryInterface',
      'Formandsystemapi\Repositories\Content\EloquentContentRepository'
    );
    $this->app->bind(
      'Formandsystemapi\Repositories\Stream\StreamRepositoryInterface',
      'Formandsystemapi\Repositories\Stream\EloquentStreamRepository'
    );
  }

}
