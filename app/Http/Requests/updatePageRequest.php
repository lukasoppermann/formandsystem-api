<?php namespace Formandsystemapi\Http\Requests;

class updatePageRequest extends BasicRequest {

	protected $scopes = ['pages.read','pages.write'];

}
