<?php namespace Formandsystemapi\Http\Requests\streams;

class deleteStreamRequest extends basicStreamRequest {

	protected $scopes = ['content.read','content.delete'];

}
