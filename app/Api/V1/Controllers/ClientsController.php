<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use \App\Api\V1\Serializer\JsonApiExtendedSerializer;
// use Ramsey\Uuid\Uuid;

class ClientsController extends ApiController
{
    /**
     * The scopes a new user can have
     *
     * @var array
     */
    protected $allowedScopes = [
        'content.get',
        'content.post',
        'content.delte',
        'content.patch'
    ];
    /**
     * The resources name
     *
     * @var array
     */
    protected $resource = 'clients';
    /*
     * create oauth user
     */
    public function store(Request $request)
    {
        // get model
        $model = $this->newModel();
        // get data from request
        $receivedData = $this->getRecivedData($request, $model->acceptedFields(['scopes']));
        // validate scopes
        $scopes = $this->parseScopes($receivedData['scopes']);
        // create Client
        $newClient = [
            'id' => bin2hex(random_bytes(20)),
            'secret' => bin2hex(random_bytes(20)),
            'name' => $request->json('data.attributes.name'),
        ];
        // create item
        $model = $model->create($newClient);
        // add scopes
        foreach($scopes as $scope){
            $client_scopes = [
                'scope_id'  => $scope,
                'client_id' => $model->id
            ];
        }
        app('db')->table('oauth_client_scopes')->insert($client_scopes);
        // return result
        return $this->response->item($model, $this->newTransformer(), ['key' => $this->resource])
            ->setStatusCode(201)
            ->withHeader('Location', $_ENV['API_DOMAIN'].'/'.$this->resource.'/'.$model->id);
    }
    /*
    * update
    */
    public function update(Request $request, $id)
    {
        // get Model
        $model = $this->newModel()->findWithTrashed($id);
        // validate item
        $this->validateResourceExists($model,
            'The resource of type "'.$this->resource.'" with the id "'.$id.'" does not exist.'
        );
        // get data from request
        $receivedData = $this->getRecivedData($request, $model->acceptedFields());
        // get relationship data from request
        $relationships = $this->getRecivedRelationships($request);
        // restore or softDelete item
        if(array_key_exists('is_trashed',$receivedData)){
            $model->setTrashed($receivedData['is_trashed']);
            unset($receivedData['is_trashed']);
        }
        // Add new data to model
        $model->fill($receivedData);
        // Save Model
        $model->save();
        // add relationships
        $this->removeRelationships($model, $relationships);
        $this->saveRelationships($model, $relationships);
        // return result
        return $this->response->item($this->newModel()->withTrashed()->where('id', $id)->first(), $this->newTransformer(), ['key' => $this->resource])->setStatusCode(200);
    }
    /*
     * delete
     */
    public function delete($resource_id){
        // validate resource
        $model = $this->validateResourceExists(
            $this->newModel()->findWithTrashed($resource_id),
            'The resource of type "'.$this->resource.'" with the id "'.$resource_id.'" does not exist.'
        );
        // delete details on delete
        $model->details()->delete();
        // force delete to really delete soft deletes
        $model->forceDelete();

        return $this->response->noContent();
    }
    /**
     * get scopes array from request
     *
     * @method getScopes
     *
     * @return [array]
     */
    protected function parseScopes($scopes){
        $scopes = array_map('trim', explode(',',$scopes));
        foreach($scopes as $scope){
            if(!in_array($scope, $this->allowedScopes)){
                throw new \Dingo\Api\Exception\ResourceException('Invalid scope requested.',['invalid scope' => $scope]);
            }
        }
        // return scopes if all are valid
        return $scopes;
    }
}
