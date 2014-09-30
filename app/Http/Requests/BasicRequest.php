<?php namespace Formandsystemapi\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;
use LucaDegasperi\OAuth2Server\Authorizer;
use Formandsystemapi\Repositories\User\UserRepositoryInterface as UserRepository;

class BasicRequest extends FormRequest{

	protected $scopes = [];

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'format' => 'in:json',
			'language' => 'alpha',
			'limit' => 'integer',
			'offset' => 'integer',
			'fields' => '',
			'status' => 'integer',
			'until' => '',
			'since' => '',
			'withDeleted' => 'string',
			'sort' => '',
			'first' => 'string',
			'position' => 'integer',
			'parent_id' => 'integer',
			'link' => 'alpha_dash',
		];
	}

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(Authorizer $authorizer, UserRepository $userRepository)
	{

		// varify correct scopes
		if ( !empty($this->scopes) and !$authorizer->hasScope($this->scopes) )
		{
			return false;
		}

		$owner = $userRepository->getByOwnerId( $authorizer->getResourceOwnerId() );

		// set db connection
		$db = array(
			'driver'    => 'mysql',
			'host'      => $owner->service_host,
			'database'  => $owner->service_name,
			'username'  => $owner->service_user,
			'password'  => $owner->service_key,
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		);

		Config::set("database.connections.user", $db);

		// return true to make authorize pass
		return true;
	}

	/**
	* Define forbidden response
	*
	* @return bool
	*/
	public function forbiddenResponse()
	{
		return Response::Json('Unauthorized',401);
	}

}
