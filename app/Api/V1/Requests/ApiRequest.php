<?php

namespace App\Api\V1\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

abstract class ApiRequest
{
    /**
     * the original request
     *
     * @var Request
     */
    protected $request;
    /**
     * an array of error messages
     *
     * @var array
     */
    protected $errors = [];
    /**
     * the resource exceptions for given methods
     *
     * @var array
     */
    private $resourceExceptions = [
        'delete'    => 'Dingo\Api\Exception\DeleteResourceFailedException',
        'get'       => 'Dingo\Api\Exception\ResourceException',
        'post'      => 'Dingo\Api\Exception\StoreResourceFailedException',
        'patch'     => 'Dingo\Api\Exception\UpdateResourceFailedException'
    ];
    /**
     * get original request
     *
     * @method __construct
     *
     * @param  Request $request
     */
    public function __construct(Request $request){
        // store current request
        $this->request = $request;
        // validate request data
        $this->validate($request);
        // validate request parameters
        $this->validateParameters($request);
    }
    /**
     * if method does not exist, delegate to request class
     *
     * @method __call
     *
     * @param  string $method_name
     * @param  $arguments
     */
    public function __call($method_name, $arguments)
    {
        // if method does not exist in this class
        if (!method_exists($this, $method_name)){
            // check request class
            if (method_exists($this, $this->request)){
                $this->request->{$method_name}($arguments);
            }
        }
    }
    /**
     * validate current request
     *
     * @method validate
     *
     * @param  Request $request
     *
     * @return void | Exception
     */
    protected function validate(Request $request){
        // get rules
        $rules = array_merge(
            // rules from resource Request
            $this->dataRules(),
            // rules for resource specific relationships
            $this->relationshipRules(),
            // add field existence validation for top level (data)
            $this->allowedDataFields(),
            // add field existence validation for top level (data)
            $this->allowedAttributes()
        );
        // run validation
        $validator = app('validator')->make(
            // wrap in data for field validation
            ['data' => $request->json('data')], $rules
        );
        // throw error if validation fails
        if($validator->fails()){
            $this->resourceException($this->error('Request data validation failed.'),$validator->errors());
        }
    }
    protected function dataRules(){
        foreach($this->rules() as $key => $value){
            $rules['data.'.$key] = $value;
        }
        return $rules;
    }
    /**
     * validate that all fields are allowed to exist
     *
     * @method validateFieldsAreAllowed
     *
     * @param  Validator              $validator
     *
     * @return [type]
     */
    protected function validateFieldsAreAllowed($validator){
        // get rules from validator
        $rules = $validator->getRules();
        $allowed_rules = [];
        foreach($rules as $key => $value){
            if(strpos($key,'.')){
                $subkey = substr($key,strpos($key,'.')+1);
                $key = substr($key,0,strpos($key,'.'));
                if($key === 'attributes'){
                    if(strpos($subkey,'.')){
                        $subkey = substr($subkey,0,strpos($subkey,'.'));
                    }
                    $allowed_rules['attributes'][$subkey] = $subkey;
                }

            }
            $allowed_rules['data'][$key] = $key;
        }

        $validator->after(function($validator) use($allowed_rules) {
            foreach(array_keys($validator->getData()) as $key){
                if(!in_array($key,$allowed_rules['data'])){
                    $validator->errors()->add('Field invalid', 'Invalid field '.$key.'. Only '.implode(', ',$allowed_rules['data']).' are allowed as immediate children of the data element.');
                }
            }
        });

        $validator->after(function($validator) use($allowed_rules) {
            foreach(array_keys($validator->getData()['attributes']) as $key){
                if(!in_array($key,$allowed_rules['attributes'])){
                    $validator->errors()->add('Field invalid', 'Invalid field '.$key.'. Only '.implode(', ',$allowed_rules['attributes']).' are valid attributes for this request.');
                }
            }
        });

        return $validator;
    }
    /**
     * validate the different parameters available for requests
     *
     * @method validateParameters
     *
     * @param  Request            $request
     *
     * @return void | Exception
     */
    protected function validateParameters(Request $request){
        // validate filters
        $this->validateFilters($request);
        // validate includes
        $this->validateIncludes($request);
        // validate includes
        $this->validateAuthorization($request);
    }
    /**
     * validate filters used in request
     *
     * @method validateFilters
     *
     * @param  Request         $request
     *
     * @return  void | Exception
     */
    protected function validateFilters(Request $request){
        // check if filters are available
        foreach((array) $this->request->input('filter') as $key => $filter){
            if(!in_array($key, $this->availableFilters())){
                $errors[$key][] = 'The filter "'.$key.'" is not available.';
            }
        }
        // throw exception in case of errors
        if(isset($errors) && count($errors) > 0){
            $this->resourceException($this->error('Malformed or unavailable filters used.'), $errors);
        }
    }
    /**
     * validate includes used in request
     *
     * @method validateIncludes
     *
     * @param  Request         $request
     *
     * @return  void | Exception
     */
    protected function validateIncludes(Request $request){
        $includes = array_filter(explode(',',$this->request->input('include')));
        // check if filters are available
        foreach($includes as $relationship){
            if(!in_array($relationship, $this->availableRelationships())){
                $errors[$relationship][] = 'The resources has not available relationship to "'.$relationship.'".';
            }
        }
        // throw exception in case of errors
        if(isset($errors) && count($errors) > 0){
            $this->resourceException($this->error('Unavailable relationship requested.'), $errors);
        }
    }
    /**
     * validate includes used in request
     *
     * @method validateIncludes
     *
     * @param  Request         $request
     *
     * @return  void | Exception
     */
    protected function validateAuthorization(Request $request){
        if(!$this->authorize($request)){
            throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException('Unauthorized request.');
        }
    }
    /**
     * add rules for resources relationships to rule array
     *
     * @method addRelationshipRules
     *
     * @param  [array]               $rules
     *
     * @return [array]
     */
    protected function addRelationshipRules($rules = []){
        // allow only available relationships
        $rules['relationships'] = 'array_has_only:'.implode(',',$this->availableRelationships());
        // add rule to check type & id of relationships
        foreach($this->availableRelationships() as $relationship){
            // get base relationship validation path
            $relationship_data = 'relationships.'.$relationship.'.data.*';
            // validate relationship type & id
            $rules[$relationship_data.'.type'] = 'in:'.$relationship.'|required_with:'.$relationship_data.'.id';
            $rules[$relationship_data.'.id']   = 'string|alpha_dash|min:3|required_with:'.$relationship_data.'.type';
        }
        // return fules
        return $rules;
    }
    /**
     * returns rules for resources relationships
     *
     * @method relationshipRules
     *
     * @return [array]
     */
    protected function relationshipRules(){
        // allow only available relationships
        $rules['data.relationships'] = 'array_has_only:'.implode(',',$this->availableRelationships());
        // add rule to check type & id of relationships
        foreach($this->availableRelationships() as $relationship){
            // get base relationship validation path
            $relationship_data = 'data.relationships.'.$relationship.'.data.*';
            // validate relationship type & id
            $rules[$relationship_data.'.type'] = 'in:'.$relationship.'|required_with:'.$relationship_data.'.id';
            $rules[$relationship_data.'.id']   = 'string|alpha_dash|min:3|required_with:'.$relationship_data.'.type';
        }
        // return fules
        return $rules;
    }
    /**
     * returns main fields in data that are allowed to exist
     *
     * @method allowedDataFields
     *
     * @return [array]
     */
    protected function allowedDataFields(){
        // values that are allowed within data in jsonapi
        $jsonapiAllowed = ['type','id','relationships','attributes','links','meta'];
        // return rule
        return [
            'data' => 'array_has_only:'.implode(',',$jsonapiAllowed)
        ];
    }
    /**
     * returns the fields allowed within attributes
     *
     * @method allowedAttributes
     *
     * @return [array]
     */
    protected function allowedAttributes(){
        // get attributes
        $attributes = array_filter(array_map(function ($key){
            if(substr($key,0,10) === 'attributes'){
                return substr($key,11);
            }
        }, array_keys($this->rules())));
        // return
        return [
            'data.attributes' => 'array_has_only:'.implode(',',$attributes)
        ];
    }
    /**
     * returns validation for fields that are allowed to exist
     *
     * @method allowedFields
     *
     * @param  Validator              $validator
     *
     * @return [array]
     */
    protected function allowedFields($input_rules){
        // get rules from validator
        foreach($input_rules as $key => $value){

            if($pos = strpos(str_replace('data.','',$key),'.')){
                // add main key to data
                $data_key = substr($key,0,$pos+5);
                $allowed['data'][$data_key] = $data_key;
                // subitem
                $subkey = substr(str_replace($data_key,'',$key),strpos(str_replace($data_key,'',$key),'.')+1);
                $allowed[$data_key][$subkey] = $subkey;
            }
            else {
                $allowed['data'][$key] = $key;
            }


        }

        foreach($allowed as $key => $array){
            $rules[$key] = 'array_has_only:'.implode(',',$array);
        }

        return $rules;
    }
    /**
     * throw new resource exception
     *
     * @method resourceException
     *
     * @param  [string]           $msg
     * @param  [array]            $array
     */
    protected function resourceException($msg, $array = []){
        // get method
        $method = strtolower($this->request->method());
        // throw exception
        throw new $this->resourceExceptions[$method]($msg,$array);
    }
    /**
     * return a message when the formrequest fails
     *
     * @method failMessage
     *
     * @return [string]
     */
    protected function error($default = ""){
        // return generic error
        return $this->errorMessage() ? $this->errorMessage() : $default;
    }
    /**
     * the error message for a request
     *
     * @method errorMessage
     *
     * @return string
     */
    protected function errorMessage(){
        return false;
    }
    /**
     * The filters that are allowed in requests
     *
     * @return array
     */
    public function availableFilters(){
        if(method_exists($this, 'filters')){
            return $this->filters();
        }
        return [];
    }
    /**
     * The relationships a resource can have
     *
     * @return array
     */
     public function availableRelationships(){
         if(method_exists($this, 'relationships')){
             return $this->relationships();
         }
         return [];
     }
    /**
     * validation rules
     *
     * @method rules
     *
     * @return array
     */
    abstract protected function rules();
    /**
     * check if request is authorize
     *
     * @method authorize
     *
     * @return bool
     */
    abstract protected function authorize();
}
