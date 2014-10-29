<?php namespace Formandsystemapi\Http\Requests\Pages;;

class updatePageRequest extends basicPageRequest {

	protected $scopes = ['content.read','content.write'];

}
