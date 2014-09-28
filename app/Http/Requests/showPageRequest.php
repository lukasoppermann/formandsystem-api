<?php namespace Formandsystemapi\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LucaDegasperi\OAuth2Server\Authorizer;

class showPageRequest extends BasicRequest {

	protected $scopes = ['pages.read'];

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

}
