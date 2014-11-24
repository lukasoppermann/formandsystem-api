<?php namespace Formandsystemapi\Http\Requests\Streams;

class updateStreamRequest extends basicStreamRequest {

	protected $scopes = ['content.read','content.write'];

}
