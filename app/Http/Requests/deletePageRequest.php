<?php namespace Formandsystemapi\Http\Requests;

class deletePageRequest extends BasicRequest {

	protected $scopes = ['pages.read','pages.delete'];

}
