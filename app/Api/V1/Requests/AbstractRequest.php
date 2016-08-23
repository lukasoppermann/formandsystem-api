<?php

namespace App\Api\V1\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

abstract class AbstractRequest
{
    /**
     * the original request
     *
     * @var Request
     */
    public $request;
    /**
     * the resource for the request
     *
     * @var resource
     */
    protected $resource;
    /**
     * determins if trash is included
     *
     * @var with_trashed
     */
    public $with_trashed = false;
    /**
     * determins if only trashed items should be returned
     *
     * @var $only_trashed
     */
    public $only_trashed = false;
    /**
     * all filter from the request
     *
     * @var $requestFilter
     */
    protected $requestFilter = [];
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
        'include',
        'exclude',
        'access_token',
    ];
    /**
     * get original request
     *
     * @method __construct
     *
     * @param  Request $request
     */
    public function __construct(Request $request){
        if($request !== NULL){
            // store current request
            $this->request = $request;
            // authorize request
            $this->isAuthorized();
            // run validation if not file request
            if(!isset($this->fileRequest) || $this->fileRequest !== TRUE){
                // validate request data
                $this->validate($this->request);
                // process fitler
                $this->processFilter($this->request);
                // validate includes
                $this->validateIncludes($this->request);
                // validate query parameters
                $this->validateQueryParameters($this->request);
            }
        }
    }
    /**
     * check if request is authorized
     *
     * @method isAuthorized
     *
     * @return void|exception
     */
    protected function isAuthorized(){
        if(method_exists($this, 'authorize') === true && $this->authorize() !== true){
            throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException(null, 'Failed to authorize the request possibley due to missing scopes.');
        }
        return true;
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
        $rules = $this->rules();
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
        // get data
        $data = $request->json('data');
        // run validation
        $validator = app('validator')->make(
            // wrap in data for field validation
            ['data' => $data], $rules
        );
        // throw error if validation fails
        if($validator->fails()){
            $this->resourceException($this->error('Request data validation failed.'),$validator->errors());
        }
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
    protected function validateFilters($filter = []){
        // check if filters are available
        foreach((array) $filter as $key => $value){
            if(!in_array($key, $this->requestFilter())){
                $errors[$key][] = 'The filter "'.$key.'" is not available.';
            }
        }
        // throw exception in case of errors
        if(isset($errors) && count($errors) > 0){
            $this->resourceException($this->error('Malformed or unavailable filters used.'), $errors);
        }
    }
    /**
     * process special filter
     *
     * @method processFilter
     *
     * @param  Request       $request
     *
     * @return [void]
     */
    protected function processFilter(Request $request){
        // get all filter
        $filter = $request->input('filter');
        // define for special filter (values are strings)
        $specialFilter = [
            'with_trashed' => 'true',
            'only_trashed' => 'true',
        ];
        // check special filter
        foreach($specialFilter as $filterName => $value){
            if(isset($filter[$filterName]) && $filter[$filterName] === $value){
                $this->$filterName = true;
            }
            unset($filter[$filterName]);
        }
        // validate filters
        $this->validateFilters($filter);
        // set filter
        if(is_array($filter)){
            $this->requestFilter = array_map(function($item){
                return array_map('trim',explode(',',$item));
            },$filter);
        }
    }
    /**
     * get valid filters
     *
     * @method filter
     *
     * @return [array]
     */
    public function filter(){
        return collect($this->requestFilter);
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
            // only get first part if dot is found e.g. pages.fragments
            if($pos = strpos($relationship,'.')){
                $relationship = substr($relationship,0,$pos);
            }
            // check relationship
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
     * returns rules for resources relationships
     *
     * @method relationshipRules
     *
     * @return [array]
     */
    protected function relationshipRules(){
        $relationshipsTypes = array_map(function($item){
            return strtolower(str_replace('ownedBy','',$item));
        },$this->relationships());
        $relationshipsTypes = implode(',',$relationshipsTypes);
        // allow only available relationships
        $rules['data.relationships'] = 'array_has_only:'.implode(',',$this->relationships());
        // add rule to check type & id of relationships
        foreach($this->relationships() as $relationship){
            // get base relationship validation path
            $relationship_data = 'data.relationships.'.$relationship.'.data';
            // validate relationship type & id of relationship ARRAY
            $rules[$relationship_data.'.*.type'] = 'in:'.$relationshipsTypes.'|required_with:'.$relationship_data.'.*.id';
            $rules[$relationship_data.'.*.id']   = 'string|alpha_dash|min:3|required_with:'.$relationship_data.'.*.type';
            // validate relationship type & id of SINGLE relationship
            $rules[$relationship_data.'.type'] = 'in:'.$relationshipsTypes.'|required_with:'.$relationship_data.'.id';
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
            if(substr($key,0,15) === 'data.attributes'){
                return substr($key,16);
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
        // throw exception
        throw new \Dingo\Api\Exception\ResourceException($msg,$array);
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
        if(isset($this->mimeTypes)){
            return in_array($this->request->header('Content-Type'),$this->mimeTypes);
        }
        return false;
    }
    /**
     * filter available for the request
     *
     * @method requestFilter
     *
     * @return array
     */
    protected function requestFilter(){
        // return method specific rules
        if(isset($this->filter) && is_array($this->filter)){
            return $this->filter;
        }
        // or empty array if none are set
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
}
