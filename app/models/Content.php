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
	protected $models = [];
	/**
	 * Enable soft deleteing
	 *
	 * @var string
	 */
	use SoftDeletingTrait;
	protected $dates = ['deleted_at'];

	/**
	* construct
	*
	* @return void
	*/
	function __construct()
	{
		$this->models = [
			'posts' => new Posts
		];
	}

	/**
	 * Define relationships
	 *
	 * @return void
	 */
	function navigation()
	{
		return $this->belongsTo('Navigation', 'article_id', 'article_id');
	}

	function posts()
	{
		return $this->belongsTo('Posts', 'article_id', 'article_id');
	}
	/**
	 * Decode data json
	 *
	 * @return object
	 */
  public function getDataAttribute($value)
	{
		$data = json_decode($value);
		if( is_object($data) || is_array($data) )
		{
			return $data;
		}
		return $value;
  }


	/**
	 * GET single page
	 *
	 * @return array
	 */
	function getPage( $id )
	{
		if( !is_numeric( $id ) && $data = $this->whereRaw('link = ? and language = ?', array($id, Config::get('content.locale')) )->first() )
		{
			$id = $data['id'];
		}
		// return Item
		return $this->find($id);
	}


	/**
	* GET content by parameters
	*
	* @return array
	*/
	function getContent( $param )
	{
		// define available parameters
		$parameters = array('language', 'type', 'status');

		// special parameters
		// limit, offset, since, until, stream

		// get the collection obj
		$collection = $this->newQuery();

		// stream
		if( isset($param['stream']) )
		{
			try {
				$posts = $this->models['posts']->getStream($param['stream'])->lists('article_id','position');
				$collection->whereIn('article_id', $posts);
			}
			catch (Exception $e)
			{
				return $e->message;
			}

		}
		// apply parameters
		foreach($param as $key => $value)
		{
			if( in_array($key, $parameters) )
			{
				$collection->where($key,$value);
			}
		}
		// if until is set
		if( isset($param['until']) && $this->_validateDate($param['until']) )
		{
			$collection->where('created_at','<=',$param['until']);
		}

		// if since is set
		if( isset($param['since']) && $this->_validateDate($param['since']) )
		{
			$collection->where('created_at','>=',$param['since']);
		}

		// set limit
		$limit = isset($param['limit']) && is_int((int) $param['limit']) ? $param['limit'] : 20;
		// set offset
		$offset = isset($param['offset']) && is_int((int) $param['offset']) ? $param['offset'] : 0;

		return $collection->skip($offset)->take($limit)->get();
	}

	/**
	* Validate a date to be YYYY-MM-DD
	*
	* @return boolean
	*/
	function _validateDate($date)
	{
		$d = DateTime::createFromFormat('Y-m-d', $date);
		return $d && $d->format('Y-m-d') == $date;
	}

}
