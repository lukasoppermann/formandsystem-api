<?php namespace Formandsystemapi\Http\Requests\pages;

class storePageRequest extends basicPageRequest {

	protected $scopes = ['content.read','content.write'];

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
			'article_id' => 'integer|required',
			'position' => 'integer|required',
			'parent_id' => 'integer|required',
			'status' => 'integer|required',
			'language' => 'alpha_dash|required',
		]);
	}

}
