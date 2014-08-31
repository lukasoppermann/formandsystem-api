<?php

use LaravelBook\Ardent\Ardent;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class ContentModel extends Ardent{

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
	 * Ardent validation rules
	 */
	public static $rules = array(
	  'article_id' => 'required|integer',
	  'status' => 'required|integer',
	  'link' => 'required|alpha_dash',
	  'language' => 'required|alpha',
		'type' => 'required|integer'
	);
	/**
	 * Define relationships
	 *
	 * @return void
	 */
	function navigation()
	{
		return $this->belongsTo(get_class(Navigation::getFacadeRoot()), 'article_id', 'article_id');
	}

	function posts()
	{
		return $this->belongsTo(get_class(Posts::getFacadeRoot()), 'article_id', 'article_id');
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
		return Content::find($id);
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
		$collection = Content::newQuery();

		// stream
		if( isset($param['stream']) )
		{
			try {
				$posts = Posts::getStream($param['stream'])->lists('article_id','position');
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
