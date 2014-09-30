<?php namespace Formandsystemapi\Http\Requests;

class storePageRequest extends BasicRequest {

	protected $scopes = ['pages.read','pages.write'];

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return array_merge( parent::rules(), [
			'stream' => 'alpha_dash|required_without:article_id',
			'parent_id' => 'integer|required',
			'position' => 'integer|required',
			'article_id' => 'integer|required_without:stream',
			'status' => 'integer|required',
			'language' => 'alpha_dash|required',
		]);
	}

}
