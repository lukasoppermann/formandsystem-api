<?php

namespace App\Api\V1\Requests;

use App\Api\V1\Models\Client;
use App\Api\V1\Traits\TranslateTrait;

trait RequestAuthorization
{
    use TranslateTrait;
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
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException('Check your client id and client secret or you access token.');
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
            // check
            if(!is_array($details) || count($details) === 0){
                throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException($this->trans('errors.missing_client_details'));
            }

            $detail_types = array_column($details,'type');
            // missing database info
            if(!in_array('database',$detail_types)){
                throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException($this->trans('errors.missing_client_database'));
            }
            // missing image ftp info
            if(isset($this->needs_ftp_image) && $this->needs_ftp_image === TRUE){
                if(!in_array('ftp_image',$detail_types)){
                    throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException($this->trans('errors.missing_client_ftp_image'));
                }
                $this->setFtp('image', json_decode($details[array_search('ftp_image', $detail_types)]['data'], true));
            }
            // missing backup ftp info
            if(isset($this->needs_ftp_backup) && $this->needs_ftp_backup === TRUE && !in_array('ftp_backup',$detail_types)){
                throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException($this->trans('errors.missing_client_ftp_backup'));
            }

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
            'charset'   => isset($db['charset']) ? $db['charset'] : 'utf8',
            'collation' => isset($db['collation']) ? $db['collation'] : 'utf8_unicode_ci',
            'prefix'    => isset($db['prefix']) ? $db['prefix'] : '',
        );
        // store connection as config
        app('config')->set('database.connections.user',$db);
    }
    /**
     * set user ftp connection config
     *
     * @method setFtp
     *
     * @param  String $type can be image|backup
     * @param  Array $db
     */
    protected function setFtp($type, Array $ftp){
        $allowd_types = ['image','backup'];
        if(!in_array($type, $allowd_types)){
            throw new \Exception($this->trans('errors.internal'));
        }
        try{
            // prepare db connection
            $ftp = array(
                'type'      => $ftp['type'],
                'host'      => $ftp['host'],
                'path'      => $ftp['path'],
                'username'  => $ftp['username'],
                'password'  => $ftp['password'],
                'ssl'       => isset($ftp['ssl']) ? $ftp['ssl'] : false,
            );
        } catch(\Exception $e) {
            throw new \Exception($this->trans('errors.internal'));
        }
        // store connection as config
        app('config')->set('user.ftp.'.$type,$ftp);
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
        throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException(null, $this->trans('errors.internal'));
    }
}
