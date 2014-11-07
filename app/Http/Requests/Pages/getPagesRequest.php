<?php namespace Formandsystemapi\Http\Requests\Pages;

class getPagesRequest extends basicPageRequest {

	protected $scopes = ['content.read'];

	/**
	* Get the validation rules that apply to the request.
	*
	* @return array
	*/
	public function rules()
	{
		return [
			'limit' 			=> 'integer',
			'offset' 			=> 'integer',
			'withTrashed'	=> 'alpha',
			'from'				=> 'date_format:Y-m-d',
			'until'				=> 'date_format:Y-m-d',
			'language' 		=> 'required|alpha|size:2',
			'parent_id'		=> 'integer'
		];
	}

}
