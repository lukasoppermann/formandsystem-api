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
     * all allowed query parameters
     *
     * @var array
     */
    private $queryParameters = [
        'filter',
        'page',
        'limit',
        'sort',
        'fields',
        'include'
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
        if($this->isFile()){
            $this->validateFile($request);
        }
        else {
            $this->validate($request);
        }
        // validate request parameters
        $this->validateParameters($request);
        // validate query parameters
        $this->validateQueryParameters($request);
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
            if (method_exists($this->request, $method_name)){
                return $this->request->{$method_name}($arguments);
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
        $rules = $this->dataRules();
        // add rules for main resource
        if(!$this->isRelationshipRequest()){
            $rules = array_merge(
                // rules from resource Request
                $rules,
                // rules for resource specific relationships
                $this->relationshipRules(),
                // add field existence validation for top level (data)
                $this->allowedDataFields(),
                // add field existence validation for top level (data)
                $this->allowedAttributes()
            );
        }
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
    /**
     * validate current request file
     *
     * @method validateFile
     *
     * @param  Request $request
     *
     * @return void | Exception
     */
    protected function validateFile(Request $request){
        // run validation
        $validator = app('validator')->make($request->all(), $this->fileRules());
        // throw error if validation fails
        if($validator->fails()){
            $this->resourceException($this->error('Uploaded file is invalid.'),$validator->errors());
        }
    }
    /**
     * return rules prefixed with data
     *
     * @method dataRules
     *
     * @return [array]
     */
    protected function dataRules(){
        $rules = [];
        // add data. to every rule
        foreach((array) $this->rules() as $key => $value){
            $rules['data.'.$key] = $value;
        }

        return $rules;
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
        $filters = $this->request->input('filter');
        if(isset($filters)){
            // check if filter is array
            if(!is_array($filters) ){
                return $this->resourceException($this->error('Malformed filter syntax.'));
            }
            // check if filters are available
            foreach($filters as $key => $filter){
                if(!in_array($key, $this->filters())){
                    $errors[$key][] = 'The filter "'.$key.'" is not available.';
                }
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
            if(!in_array($relationship, $this->relationships())){
                $errors[$relationship][] = 'The resources has not available relationship to "'.$relationship.'".';
            }
        }
        // throw exception in case of errors
        if(isset($errors) && count($errors) > 0){
            $this->resourceException($this->error('Unavailable relationship requested.'), $errors);
        }
    }
    /**
     * validate query parameters
     *
     * @method validateQueryParameters
     *
     * @param  Request         $request
     *
     * @return  void | Exception
     */
    protected function validateQueryParameters(Request $request){
        // get suporfluous parameters
        $unknown_parameters = array_diff( array_keys($request->query()), $this->queryParameters );
        // return error of invalid argumens are supplied
        if(count($unknown_parameters) > 0){
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException('Invalid query parameter supplied: "'.trim(implode(', ',$unknown_parameters),', ').'".');
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
     * returns rules for resources relationships
     *
     * @method relationshipRules
     *
     * @return [array]
     */
    protected function relationshipRules(){
        // allow only available relationships
        $rules['data.relationships'] = 'array_has_only:'.implode(',',$this->relationships());
        // add rule to check type & id of relationships
        foreach($this->relationships() as $relationship){
            // get base relationship validation path
            $relationship_data = 'data.relationships.'.$relationship.'.data';
            // validate relationship type & id of relationship ARRAY
            $rules[$relationship_data.'.*.type'] = 'in:'.$relationship.'|required_with:'.$relationship_data.'.*.id';
            $rules[$relationship_data.'.*.id']   = 'string|alpha_dash|min:3|required_with:'.$relationship_data.'.*.type';
            // validate relationship type & id of SINGLE relationship
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
        $jsonapiAllowed = ['type','relationships','attributes','links','meta'];
        // add id only if request is PATCH
        if($this->request->method() === "PATCH"){
            $jsonapiAllowed[] = 'id';
        }
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
        }, array_keys((array) $this->rules())));
        // return
        return [
            'data.attributes' => 'array_has_only:'.implode(',',$attributes)
        ];
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
        if(method_exists($this, 'errorMessage')){
            return $this->errorMessage();
        }
        return $default;
    }
    /**
     * check if the current request is a relationship request
     *
     * @method isRelationshipRequest
     *
     * @return [bool]
     */
    protected function isRelationshipRequest(){
        return $this->request->segment(3) === 'relationships';
    }
    /**
     * check if the current request is fioe upload
     *
     * @method isFile
     *
     * @return [bool]
     */
    protected function isFile(){
        return count($this->request->allFiles()) > 0;
    }
    /**
     * filters available for the request
     *
     * @method filters
     *
     * @return array
     */
    abstract protected function filters();
    /**
     * image validation rules
     *
     * @method fileRules
     *
     * @return array
     */
    protected function fileRules(){
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
    /**
     * The relationships the main resource can have
     *
     * @method relationships
     *
     * @return array
     */
    abstract protected function relationships();
}
