<?php namespace Formandsystemapi\Http\Requests\Streams;

class deleteStreamRequest extends basicStreamRequest {

	protected $scopes = ['content.read','content.delete'];

}
