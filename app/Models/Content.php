<?php namespace Formandsystemapi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Content extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $connection = 'user';
	protected $table = 'fs_content';
	protected $guarded = ['id'];
	/**
	 * Enable soft deleteing
	 *
	 * @var string
	 */
	protected $dates = ['deleted_at'];

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
	function stream()
	{
		return $this->belongsTo('Formandsystemapi\Models\Stream', 'article_id', 'article_id');
	}

	function tags()
	{
		return $this->belongsToMany('Formandsystemapi\Models\Tags', 'fs_content_tags', 'content_id', 'tag_id');
	}

	function fragments()
	{
		return $this->belongsToMany('Formandsystemapi\Models\Fragments', 'fs_content_fragments', 'content_id', 'fragment_id');
	}

}
