<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Content extends Eloquent{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $connection = 'user';
	protected $table = 'fs_content';
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
