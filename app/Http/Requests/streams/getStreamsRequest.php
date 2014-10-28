<?php namespace Formandsystemapi\Http\Requests\streams;

class getStreamsRequest extends basicStreamRequest {

	protected $scopes = ['content.read'];

	/**
	* Get the validation rules that apply to the request.
	*
	* @return array
	*/
	public function rules()
	{
		return array_merge( parent::rules(), [
			'limit' 			=> 'integer',
			'parent_id' 	=> 'integer',
			'stream' 			=> 'required'
		]);
	}

}
