<?php namespace Formandsystemapi\Http\Requests\Streams;

class getStreamsRequest extends basicStreamRequest {

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
		];
	}

}
