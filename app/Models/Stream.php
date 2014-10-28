<?php namespace Formandsystemapi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Stream extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $connection = 'user';
	protected $table = 'fs_stream';
	protected $fillable = array('parent_id','article_id','stream','position');
	protected $primaryKey = 'id';
	public $timestamps = false;

	/**
	* Enable soft deleteing
	*
	* @var string
	*/
	use SoftDeletingTrait;
	protected $dates = ['deleted_at'];

	/**
	 * Define relationships
	 *
	 * @return void
	 */
	function content()
	{
		return $this->hasMany('Formandsystemapi\Models\Content', 'article_id', 'article_id');
	}

}
