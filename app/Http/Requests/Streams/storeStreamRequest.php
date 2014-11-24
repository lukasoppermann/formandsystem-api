<?php namespace Formandsystemapi\Http\Requests\Streams;

class storeStreamRequest extends basicStreamRequest {

	protected $scopes = ['content.read','content.write'];

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return array_merge( parent::rules(), [
			'position' 		=> 'integer',
			'parent_id' 	=> 'integer',
			'stream' 			=> 'required'
		]);
	}

}
