<?php namespace Formandsystemapi\Http\Requests\pages;

class deletePageRequest extends basicPageRequest {

	protected $scopes = ['content.read','content.delete'];

}
