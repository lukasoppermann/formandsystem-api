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
		$config = array_merge($config, (array)$this->config);
		
		if( substr($path, 0, 4) !== 'http' && (!isset($config['url']) || $config['url'] == "") )
		{
			throw new Exception('Request URL is not specified correctly.');
		}
		else
		{
			$config['url'] = (substr($path, 0, 4) == 'http' ? $path : $config['url'].$path);
		}
		
		return $config;
	}
	/**
	 * get
	 *
	 * get request
	 *
	 * @access	public
	 */
	public function get( $path, $config = array() )
	{
		$config = $this->merge_config($path, $config);
		
		return $this->client->get(url($config['url']), ['auth' => [$config['user'], $config['password']]]);
	}
	/**
	 * get
	 *
	 * get request
	 *
	 * @access	public
	 */
	// public function get( $path )
	// {
	// 	return $client->get(url('http://www/formandsystem/public/api/v1/stream/navigation.json'), ['auth' =>  ['lukas@vea.re', 'lukas']]);
	// }
	
}