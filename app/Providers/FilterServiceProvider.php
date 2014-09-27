<?php namespace Formandsystem\Providers;

use Illuminate\Routing\FilterServiceProvider as ServiceProvider;

class FilterServiceProvider extends ServiceProvider {

	/**
	 * The filters that should run before all requests.
	 *
	 * @var array
	 */
	protected $before = [
		'Formandsystem\Http\Filters\MaintenanceFilter',
	];

	/**
	 * The filters that should run after all requests.
	 *
	 * @var array
	 */
	protected $after = [
		//
	];

	/**
	 * All available route filters.
	 *
	 * @var array
	 */
	protected $filters = [
		'auth' => 'Formandsystem\Http\Filters\AuthFilter',
		'auth.basic' => 'Formandsystem\Http\Filters\BasicAuthFilter',
		'csrf' => 'Formandsystem\Http\Filters\CsrfFilter',
		'guest' => 'Formandsystem\Http\Filters\GuestFilter',
		'api.auth' => 'Formandsystem\Http\Filters\ApiAuthFilter',
	];

}
