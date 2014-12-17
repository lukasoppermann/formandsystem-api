<?php namespace Formandsystemapi\Models;

use Illuminate\Database\Eloquent\Model;

class Fragments extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $connection = 'user';
	protected $table = 'fs_fragments';
	protected $fillable = array('key','data');
	public $timestamps = true;

	/**
	* Attribute getters
	*
	* @return void
	*/
	public function getDataAttribute($value)
	{
		return json_decode($value, true);
	}

	/**
	 * Define relationships
	 *
	 * @return void
	 */
	function content()
	{
		return $this->belongsToMany('Formandsystemapi\Models\Content', 'fs_content_fragments', 'fragment_id', 'content_id');
	}

}
