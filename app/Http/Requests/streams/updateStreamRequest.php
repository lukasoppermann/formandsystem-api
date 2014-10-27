<?php namespace Formandsystemapi\Http\Requests\streams;

class updateStreamRequest extends basicStreamRequest {

	protected $scopes = ['content.read','content.write'];

}
