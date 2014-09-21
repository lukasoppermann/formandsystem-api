<?php

class BaseApiController extends Controller {

	public $parameters = array();

	function __construct()
	{
		// set default parameters
		$this->parameters = array(
			// parameters for get requests & defaults
			'get' => array(
				'default' => array(
					'format' => 'json',
					'language' => 'en',
					'limit' => 20,
					'offset' => 0,
					'fields' => '*',
					'status' => '1',
					'until' => false,
					'since' => false,
					'withDeleted' => false,
					'sort' => 'id,position',
					'first' => false,
				),
				'accepted' => array(
					'format' => 'in:json',
					'language' => 'alpha',
					'limit' => 'integer',
					'offset' => 'integer',
					'fields' => '',
					'status' => 'integer',
					'until' => '',
					'since' => '',
					'withDeleted' => '',
					'sort' => '',
					'first' => '',
				)
			),
		);


		// header('Access-Control-Allow-Origin: '.Auth::user()->service_url);
		// header('Access-Control-Allow-Origin: http://cms.formandsystem.com');
	  // Allow from any origin
		header('content-type: application/json; charset=utf-8');
		header('Access-Control-Allow-Origin: http://cms.formandsystem.com');
		header('Access-Control-Allow-Credentials: true');
		header('Access-Control-Max-Age: 86400');    // cache for 1 day
		header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS");

    // Access-Control headers are received during OPTIONS requests
    // if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    //
    //     if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
    //         header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS");
    //
    //     if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
    //         header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    //
    //     exit(0);
    // }
	}


	/**
	* get the parameters from URL and merge
	*
	* @return Array
	*/
	function getParameters($type = 'get', $defaults = null, $accepted = null)
	{
		if( isset($this->parameters[$type]) )
		{
			// get defaults
			$parameters = (!isset($defaults) ? $this->parameters[$type] : $defaults);
			$accepted = (!isset($accepted) ? $this->parameters[$type.'Accepted'] : $accepted);

			// check and overwrite
			foreach(Input::all() as $parameter => $value)
			{
				// check for valid parameter key
				if( array_key_exists($parameter, $parameters) )
				{
					// check for valid parameter value if defaults are set
					if( !isset($accepted[$parameter]) || in_array($value, $accepted[$parameter]) )
					{
						$parameters[$parameter] = $value;
					}
					else
					{
						$errors[] = 'Invalid value given: \''.$value.'\' for parameter \''.$parameter.
												'\'. Accepted values: \''.implode('|',$accepted[$parameter]).'\'';
					}
				}
				else
				{
					$errors[] = 'Invalid parameter given: \''.$parameter.'\' with value \''.$value.'\'';
				}
			}

			// check for error reporting
			if( ( !isset($parameters['failSilent']) || $parameters['failSilent'] === 'false' ) && isset($errors) )
			{
				$parameters['errors'] = $errors;
			}

			return $parameters;

		}

		throw new Exception('Wrong type for parameters given. Type: \''.$type.'\' does not exist.');
	}


	/**
	* get the validate parameters
	*
	* @return Array
	*/
	function validateParameters($type = 'get', $tmp_parameters = null)
	{
		// check if nessesary parameters are given
		if( !isset($this->parameters[$type]) || !isset($tmp_parameters) ){
			return false;
		}

		// get parameters ONLY accepted items from input
		foreach($this->parameters[$type]['accepted'] as $key => $validation)
		{
			if( isset($tmp_parameters[$key]) )
			{
				$parameters[$key] = $tmp_parameters[$key];
			}
			elseif( isset($this->parameters[$type]['default']) && isset($this->parameters[$type]['default'][$key]) )
			{
				$parameters[$key] = $this->parameters[$type]['default'][$key];
			}
		}

		// validate input
		$validator = Validator::make($parameters,$this->parameters[$type]['accepted']);

		// get validation messages
		$messages = $validator->messages();

		// if validation fails, return error
		if( $messages->count() !== 0 )
		{
			return array('errors' => $messages);
		}
		// if validation succeeds, return parameters
		return $parameters;

	}



}
