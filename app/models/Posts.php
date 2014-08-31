<?php
use LaravelBook\Ardent\Ardent;

class PostsModel extends Ardent{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $connection = 'user';
	protected $table = 'fs_posts';
	/**
	 * Define relationships
	 *
	 * @return void
	 */
	function content()
	{
		return $this->hasOne(Content::getFacadeRoot(), 'article_id', 'article_id');
	}

	/**
	 * GET stream
	 *
	 * @return array
	 */
	function getStream( $stream = null )
	{
		if( $stream == null )
		{
			throw new Exception('No valid stream given.');
		}

		// get the collection obj
		$collection = Posts::where('stream',$stream);

		return $collection->get();
	}

	/**
	* GET stream ids
	*
	* @return array
	*/
	function getStreamItems( $stream = null )
	{
		if( $stream == null )
		{
			throw new Exception('No valid stream given.');
		}

		// get the collection obj
		$collection = Posts::where('stream',$stream);

		return $collection->get();
	}

}
