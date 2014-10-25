<?php namespace Formandsystemapi\Http\Requests\pages;

class updatePageRequest extends basicPageRequest {

	protected $scopes = ['content.read','content.write'];

}
