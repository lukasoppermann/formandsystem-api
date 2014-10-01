<?php namespace Formandsystemapi\Http\Requests;

class storePageRequest extends BasicRequest {

	protected $scopes = ['pages.read','pages.write'];

	// protected $redirectRoute = "v1.pages.store";

	// protected $redirectAction = "Formandsystemapi\Http\Controllers\PagesApiController@error";

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return array_merge( parent::rules(), [
			'stream' => 'alpha_dash|required_without:article_id',
			'article_id' => 'integer|required_without:stream',
			'position' => 'integer|required',
			'parent_id' => 'integer|required',
			'status' => 'integer|required',
			'language' => 'alpha_dash|required',
		]);
	}

}
