<?php namespace Formandsystemapi\Http\Requests\Streams;

class showStreamRequest extends basicStreamRequest {

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
			'language'		=> 'alpha|required|size:2'
		];
	}

}
