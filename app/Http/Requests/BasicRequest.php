<?php namespace Formandsystemapi\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;
use Formandsystemapi\Http\respond;
use LucaDegasperi\OAuth2Server\Authorizer;
use Formandsystemapi\Repositories\User\UserRepositoryInterface as UserRepository;

class BasicRequest extends FormRequest{

	protected $scopes = [];
	protected $respond;


	public function __construct(respond $respond)
	{
		$this->respond = $respond;
	}

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(Authorizer $authorizer, UserRepository $userRepository)
	{

		// varify correct scopes
		if ( !is_object($authorizer) OR ( !empty($this->scopes) and !$this->checkScopes($authorizer, $this->scopes) ) )
		{
			return false;
		}

		// varify owner exists
		if( !$owner = $userRepository->getByOwnerId( $authorizer->getResourceOwnerId() ) )
		{
			return false;
		}

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

		// save owner data for Accept cross origin url header
		Config::set("owner", $owner);

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
		return $this->respond->Unauthorized();
	}

	/**
	 * respond with validation error
	 *
	 * @method response
	 *
	 * @param  array    $errors
	 *
	 * @return Response::Json
	 */
	public function response(array $errors)
	{
		foreach($errors as $key => $err)
		{
			$errs[] = implode(' ',$err);
		}

		return $this->respond->unprocessableContent( implode(' ',$errs) );
	}

	/**
	 * Check array of scopes
	 *
	 * @method checkScopes
	 *
	 * @param  Authorizer  $authorizer
	 * @param  array       $scopes
	 *
	 * @return boolean
	 */
	private function checkScopes(Authorizer $authorizer, $scopes)
	{
		foreach($scopes as $scope)
		{
			if( !$authorizer->hasScope($scope) )
			{
				return false;
			}
		}

		return true;
	}


}
