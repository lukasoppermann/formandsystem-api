<?php namespace Formandsystemapi\Models;

use Illuminate\Database\Eloquent\Model;

class Tags extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $connection = 'user';
	protected $table = 'fs_tags';
	protected $fillable = array('name','internal');
	public $timestamps = false;

	/**
	 * Define relationships
	 *
	 * @return void
	 */
	function content()
	{
		return $this->belongsToMany('Formandsystemapi\Models\Content', 'fs_content_tags', 'tag_id', 'content_id');
	}

}
