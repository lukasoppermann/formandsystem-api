<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Models\Metadetail;
use App\Api\V1\Transformers\MetadetailTransformer;
use App\Api\V1\Validators\MetadetailValidator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MetadetailsController extends ApiController
{
    /**
     * The filters that are allowed in requests
     *
     * @var array
     */
    protected $availableFilters = [
        'type'
    ];
    /**
     * The relationships a resource can have
     *
     * @var array
     */
    protected $relationships = [
        'pages'
    ];
    /**
     * The filters that are allowed in requests
     *
     * @var array
     */
    protected $resource = 'metadetails';
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
     * update relationships
     */
    public function updatePagesRelationships(Request $request, $metadetail_id){
        $receivedRelationships = $this->getRecivedRelationships($request);
        return $receivedRelationships;
        // $data = json_decode($request->getContent(), true);
        // $metadetail = $this->validateResourceExists(Metadetail::find($metadetail_id));
        //
        // foreach($data['data'] as $relationship){
        //     $metadetail->pages()->detach($relationship['id']);
        // }
        //
        // return $this->response->noContent();
    }
    /*
     * delete relationship
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
