<?php

namespace App\Api\V1\Requests;

use App\Api\V1\Models\Client;

trait RequestAuthorization
{
    /**
     * check if request is authorize
     *
     * @method authorize
     *
     * @return bool
     */
    protected function authorize()
    {
        $authorizer = app('oauth2-server.authorizer');
        try {
            $authorizer->validateAccessToken();
        } catch( \League\OAuth2\Server\Exception\AccessDeniedException $e){
            \LOG::Debug($e);
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException('Check your client id and client secret.');
        }
        // get needed scope
        $needed_scope = $this->getScope(strtolower($this->request->getMethod()));
        // validate scope

        if(!$authorizer->hasScope($needed_scope)){
            return false;
        }
        // varify owner exists
        if( !$client = (new Client)->find($authorizer->getClientId()) )
        {
            return false;
        }

        // get all details
        if(!isset($this->setClientData) || $this->setClientData === true){
            $details = $client->details->toArray();

            $detail_types = array_column($details,'type');
            // set database
            $this->setDb(json_decode($details[array_search('database', $detail_types)]['data'], true));
        }
        // return true to make authorize pass
        return true;
    }
    /**
     * set user db connection config
     *
     * @method setDb
     *
     * @param  Array $db
     */
    protected function setDb(Array $db){
        // prepare db connection
        $db = array(
            'driver'    => $db['driver'],
            'host'      => $db['host'],
            'database'  => $db['database'],
            'username'  => $db['username'],
            'password'  => $db['password'],
            'charset'   => $db['charset'],
            'collation' => $db['collation'],
            'prefix'    => $db['prefix'],
        );
        // store connection as config
        app('config')->set('database.connections.user',$db);
    }
    /**
     * get scope for current request type and current request
     *
     * @method getScope
     *
     * @return [array]
     */
    public function getScope($method){
        // return scope
        if( isset($this->scopes) && is_array($this->scopes) && isset($this->scopes[$method]) ){
            return $this->scopes[$method];
        }
        // scope is disabled
        if($this->scopes === false){
            return [];
        }
        // log error
        \LOG::error('No scope defined for "'.class_basename($this).'".');
        throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException(null, 'Failed to authorize the request due to an internal error. Please notify support@formandsystem.com.');
    }
}
