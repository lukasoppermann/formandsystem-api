<?php namespace Formandsystemapi\Http\Requests\Pages;

class storePageRequest extends basicPageRequest {

	protected $scopes = ['content.read','content.write'];

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
