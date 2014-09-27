<?php namespace Formandsystemapi\Providers;

use Illuminate\Routing\FilterServiceProvider as ServiceProvider;

class FilterServiceProvider extends ServiceProvider {

	/**
	 * The filters that should run before all requests.
	 *
	 * @var array
	 */
	protected $before = [
		'Formandsystemapi\Http\Filters\MaintenanceFilter',
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
		'auth' => 'Formandsystemapi\Http\Filters\AuthFilter',
		'auth.basic' => 'Formandsystemapi\Http\Filters\BasicAuthFilter',
		'csrf' => 'Formandsystemapi\Http\Filters\CsrfFilter',
		'guest' => 'Formandsystemapi\Http\Filters\GuestFilter',
		'api.auth' => 'Formandsystemapi\Http\Filters\ApiAuthFilter',
	];

}
