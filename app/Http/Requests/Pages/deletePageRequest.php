<?php namespace Formandsystemapi\Http\Requests\Pages;

class deletePageRequest extends basicPageRequest {

	protected $scopes = ['content.read','content.delete'];

}
