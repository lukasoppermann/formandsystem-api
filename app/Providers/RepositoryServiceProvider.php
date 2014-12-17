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
    // Content Repository
    $this->app->bind(
      'Formandsystemapi\Repositories\Content\ContentRepositoryInterface',
      'Formandsystemapi\Repositories\Content\EloquentContentRepository'
    );

    // Stream Repository
    $this->app->bind(
      'Formandsystemapi\Repositories\Stream\StreamRepositoryInterface',
      'Formandsystemapi\Repositories\Stream\EloquentStreamRepository'
    );

    // User Repository
    $this->app->bind(
      'Formandsystemapi\Repositories\User\UserRepositoryInterface',
      'Formandsystemapi\Repositories\User\EloquentUserRepository'
    );

    // Settings Repository
    $this->app->bind(
    'Formandsystemapi\Repositories\Settings\SettingsRepositoryInterface',
    'Formandsystemapi\Repositories\Settings\EloquentSettingsRepository'
  );
  }

}
