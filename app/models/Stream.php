<?php

class Stream extends Eloquent{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $connection = 'user';
	protected $table = 'fs_stream';
	public $timestamps = false;
	public $fillable = ['parent_id','article_id','stream','position'];

	/**
	 * Define relationships
	 *
	 * @return void
	 */
	function content()
	{
		return $this->hasMany('Content', 'article_id', 'article_id');
	}

}
