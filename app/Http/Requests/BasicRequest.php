<?php namespace Formandsystemapi\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class BasicRequest extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			//
		];
	}

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		Auth::once(['email' => 'lukas@vea.re', 'password' => 'lukas']);

		if ( ! Auth::check() )
		{
			return false;
		}

		$user = Auth::user();
		// set db connection
		$db = array(
			'driver'    => 'mysql',
			'host'      => $user->service_host,
			'database'  => $user->service_name,
			'username'  => $user->service_user,
			'password'  => $user->service_key,
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		);

		Config::set("database.connections.user", $db);
		
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
