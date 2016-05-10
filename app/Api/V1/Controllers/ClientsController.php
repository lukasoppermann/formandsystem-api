<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use \App\Api\V1\Serializer\JsonApiExtendedSerializer;
use Ramsey\Uuid\Uuid;

class ClientsController extends ApiController
{
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
        $newClient = [
            'id' => bin2hex(random_bytes(30)),
            'secret' => bin2hex(random_bytes(30)),
            'name' => $request->json('data.attributes.name'),
        ];
        // create item
        $model = $this->newModel()->create($newClient);
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
}
