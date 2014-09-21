<?php namespace Formandsystem\Api;
/*
 * API
 *
 * (c) Lukas Oppermann – vea.re
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @version
 */

use Log;
use App;
use URL;
use GuzzleHttp;
use Exception;

class Api {

	// global config
	public $config;

	// global path
	public $path;

	// global client variable
	public $client;

	public function __construct()
	{
		$this->client = new GuzzleHttp\Client();
	}
	/**
	 * config
	 *
	 * set config
	 *
	 * @access	public
	 */
	public function config( $config = null )
	{
		if( isset($config['url']) && substr($config['url'], 0, 4) !== 'http' )
		{
			throw new Exception('The base URL is not specified correctly in your call API::config()');
		}
		else
		{
			$this->path = $config['url'];
			unset($config['url']);
		}

		$this->config = $config;
	}
	/**
	 * path
	 *
	 * build path
	 *
	 * @access	public
	 */
	public function path( $new_path )
	{
		// prepare url
		if( substr($new_path, 0, 4) !== 'http' && (!isset($this->path) || $this->path == "") )
		{
			throw new Exception('Request URL is not specified correctly.');
		}
		else
		{
			return (substr($new_path, 0, 4) == 'http' ? $new_path : trim($this->path,'/').'/'.trim($new_path, '/'));
		}
	}
	/**
	 * call_method
	 *
	 * call_method request
	 *
	 * @access	public
	 */
	public function call_method($fn, $path = null, $config = array(), $returnObj = false)
	{
		try{
			$req = $this->client->$fn(url($this->path($path)), array_merge((array)$this->config, (array)$config) );

			if($returnObj !== true)
			{
				return $req->json();
			}
			return $req;
		}
		catch(GuzzleHttp\Exception\ClientException $e)
		{
			if($e->getCode() == 401)
			{
				Log::error('Wrong credentials for Api call to '.$this->path($path));
				return false;
			}

			Log::error($e->getCode().': '.$e->getMessage().' on '.$this->path($path));
			return false;
		}
	}
	/**
	 * get
	 *
	 * get request
	 *
	 * @access	public
	 */
	public function get( $path = null, $config = array(), $returnObj = false )
	{
		return $this->call_method('get', $path, $config, $returnObj);
	}
	/**
	 * delete
	 *
	 * delete request
	 *
	 * @access	public
	 */
	public function delete( $path, $config = array(), $returnObj = false )
	{
		return $this->call_method('delete', $path, $config, $returnObj);
	}
	/**
	 * post
	 *
	 * post request
	 *
	 * @access	public
	 */
	public function post( $path, $config = array(), $returnObj = false )
	{
		return $this->call_method('post', $path, $config, $returnObj);
	}
	/**
	 * put
	 *
	 * put request
	 *
	 * @access	public
	 */
	public function put( $path, $config = array(), $returnObj = false )
	{
		return $this->call_method('put', $path, $config, $returnObj);
	}
	/**
	 * client
	 *
	 * get guzzle client
	 *
	 * @access	public
	 */
	public function client()
	{
		return $this->client;
	}
}
