<?php

class BaseApiController extends Controller {

	public $parameters = array();

	function __construct()
	{
		// set default parameters
		$this->parameters = array(
			// parameters for get requests & defaults
			'get' => array(
				'format' => 'json',
				'language' => 'en',
				'limit' => 20,
				'offset' => 0,
				'fields' => '*',
				'until' => false,
				'since' => false,
				'sort' => 'id,pos',
				'failSilent' => true,
			),
			// accepted parameter values for get requests
			'getAccepted' => array(
				'format' => array('json'),
			)
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
	function getParameters($type = 'get')
	{
		if( isset($this->parameters[$type]) )
		{
			// get defaults
			$parameters = $this->parameters[$type];
			$accepted = $this->parameters[$type.'Accepted'];

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
			if( $parameters['failSilent'] === 'false' && isset($errors) )
			{
				$parameters['errors'] = $errors;
			}

			return $parameters;

		}

		throw new Exception('Wrong type for parameters given. Type: \''.$type.'\' does not exist.');
	}

}
