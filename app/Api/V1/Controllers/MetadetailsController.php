<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Models\Metadetail;
use App\Api\V1\Transformers\MetadetailTransformer as MetadetailTransformer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MetadetailsController extends ApiController
{
    protected $availableFilters = [
        'type'
    ];

    public function index(Request $request)
    {
        $metadetails = $this->getFilteredResult(new Metadetail, $request->input('filter'));

        return $this->response->paginator($metadetails, new MetadetailTransformer, ['key' => 'metadetails']);
    }
    /*
     * store
     */
     public function store(Request $request, MetadetailTransformer $transformer)
     {
        $input = $transformer->receivedDataJson($request->data);
        // validate
        $model = new Metadetail;
        $id = $model->create($input)->id;

        return $model::find($id);
     }
    /*
     * show
     */
    public function show(Request $request, $metadetail_id)
    {
        $metadetails = $this->validateResourceExists(Metadetail::find($metadetail_id));

        return $this->response->item($metadetails, new MetadetailTransformer, ['key' => 'metadetails']);
    }
    /*
     * delete
     */
    public function delete($metadetail_id){
        try {
            Metadetail::destroy($metadetail_id);
            return $this->response->noContent();
        } catch (ModelNotFoundException $e) {
            return $this->response->errorNotFound();
        }
    }
    /*
     * get related
     */
    public function getPages(Request $request, $metadetail_id){
        $metadetail = $this->validateResourceExists(Metadetail::find($metadetail_id));
        // return related objects
        return $this->getRelated($request,
            $metadetail->pages(),
            'pages'
        );
    }
    /*
     * get relationship
     */
    public function getPagesRelationships(Request $request, $metadetail_id){
        $metadetail = $this->validateResourceExists(Metadetail::find($metadetail_id));
        // return relationship
        return $this->getRelationship([
            'ids' => $metadetail->pages->lists('id'),
            'type' => 'pages',
            'parent_id' => $metadetail_id,
            'parent_type' => 'metadetails'
        ]);
    }
    /*
     * get relationship
     */
    public function deletePagesRelationships(Request $request, $metadetail_id){
        $data = json_decode($request->getContent(), true);
        $metadetail = $this->validateResourceExists(Metadetail::find($metadetail_id));

        foreach($data['data'] as $relationship){
            $metadetail->pages()->detach($relationship['id']);
        }

        return $this->response->noContent();
    }

}
