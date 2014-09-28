<?php namespace Formandsystemapi\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LucaDegasperi\OAuth2Server\Authorizer;

class storePageRequest extends BasicRequest {

	protected $scopes = ['pages.read','pages.write'];

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'stream' => 'alpha_dash|required_without:article_id',
			'parent_id' => 'integer|required',
			'position' => 'integer|required',
			'article_id' => 'integer|required_without:stream',
			'link' => 'alpha_dash',
			'status' => 'integer|required',
			'language' => 'alpha_dash|required',
		];
	}

}
