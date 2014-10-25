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
		return $this->respond->respond->Unauthorized();
	}

	public function response(array $errors)
	{
		foreach($errors as $key => $err)
		{
			$errs[] = implode($err);
		}

		return $this->respond->unprocessableContent( implode($errs) );
	}


}
