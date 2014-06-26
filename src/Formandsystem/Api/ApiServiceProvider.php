<?php namespace Formandsystem\Api;

use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;
  
	/**
   * Booting
   */
	public function boot()
	{
		$this->package('formandsystem/api');
	}
	
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
    $this->app['Api'] = $this->app->share(function($app)
    {
        return new Api;
    });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('api');
	}

}
