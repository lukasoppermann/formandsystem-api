<?php namespace Formandsystemapi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

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
	use SoftDeletingTrait;
	protected $dates = ['deleted_at'];

	/**
	 * Define relationships
	 *
	 * @return void
	 */
	function stream()
	{
		return $this->belongsTo('Stream', 'article_id', 'article_id');
	}

}
