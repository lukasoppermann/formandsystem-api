<?php namespace Formandsystemapi\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	public $hidden = array('id','owner_id','created_at','updated_at');

}
