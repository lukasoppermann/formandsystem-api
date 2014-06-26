<?php namespace Formandsystem\Api;

use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;
  
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
		$this->app['api'] = $this->app->share(function($app)
		{
			return new Api;
		});
		
		$this->app->booting(function()
		{
		  $loader = \Illuminate\Foundation\AliasLoader::getInstance();
		  $loader->alias('Api', 'Formandsystem\Api\Facades\Api');
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
