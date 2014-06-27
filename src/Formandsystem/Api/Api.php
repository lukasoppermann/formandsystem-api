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
	
	// global auth
	public $auth = ['user', 'password'];
	
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
	public function config( $config )
	{
		if( isset($config['url']) && substr($config['url'], 0, 4) !== 'http' )
		{
			throw new Exception('The base URL is not specified correctly in your call API::config()');
		}
		
		$this->config = array_merge($config, (array)$this->config);
	}
	/**
	 * merge config
	 *
	 * merge config settings
	 *
	 * @access	public
	 */
	public function merge_config( $path, $config = array() )
	{
		// prepare url
		if( substr($path, 0, 4) !== 'http' && (!isset($config['url']) || $config['url'] == "") )
		{
			throw new Exception('Request URL is not specified correctly.');
		}
		else
		{
			$this->config['url'] = (substr($path, 0, 4) == 'http' ? $path : $config['url'].$path);
		}
		//
		// // preapre auth
		// foreach( $this->auth as $item )
		// {
		// 	if( !isset($config[$item]) || $config[$item] == "" )
		// 	{
		// 		$do_auth = false;
		// 		return false;
		// 	}
		// 	else
		// 	{
		// 		$do_auth = true;
		// 		$auth[] = $config[$item];
		// 	}
		// }
		//
		// if( isset($do_auth) && $do_auth == true )
		// {
		// 	$config['auth'] = $auth;
		// }
		// else
		// {
		// 	$config['auth'] = "";
		// }
		//
		// return $config;
	}
	/**
	 * call_method
	 *
	 * call_method request
	 *
	 * @access	public
	 */
	public function call_method($fn, $path, $config = array(), $returnObj = false)
	{
		$path = $this->merge_config($path, $config);
		
		// if( $this->check_auth($config) )
		// {
			try{
				if($returnObj === false)
				{
					return $this->client->$fn(url($path), $config)->getBody();
				}
				else
				{
					return $this->client->$fn(url($path), $config);
				}
			}
			catch(GuzzleHttp\Exception\ClientException $e)
			{
				if($e->getCode() == 401)
				{
					Log::error('Wrong credentials for Api call to '.$config['url']);
					return false;
				}
			}
		// }
		// else
		// {
		// 	if($returnObj === false)
		// 	{
		// 		return $this->client->$fn(url($config['url']))->getBody();
		// 	}
		// 	else
		// 	{
		// 		return $this->client->$fn(url($config['url']));
		// 	}
		// }
	}
	/**
	 * get
	 *
	 * get request
	 *
	 * @access	public
	 */
	public function get( $path, $config = array(), $returnObj = false )
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