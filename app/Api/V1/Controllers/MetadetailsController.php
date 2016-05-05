<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Models\Metadetail;
use App\Api\V1\Transformers\MetadetailTransformer;
use App\Api\V1\Validators\MetadetailValidator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
/**
 * Metadetails resource representation.
 *
 * @Resource("Metadetails", uri="/metadetails")
 */
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
    // protected $relationships = [
    //     'pages'
    // ];
    /**
     * The resources name
     *
     * @var array
     */
    protected $resource = 'metadetails';
    // /*
    //  * get related
    //  */
    // public function getPages(Request $request, $id){
    //     $model = $this->validateResourceExists($this->model->find($id));
    //     // return related objects
    //     return $this->getRelated($request,
    //         $model->pages(),
    //         'pages'
    //     );
    // }
    // /*
    //  * get relationship
    //  */
    // public function getPagesRelationships(Request $request, $metadetail_id){
    //     $metadetail = $this->validateResourceExists($this->model->find($metadetail_id));
    //     // return relationship
    //     return $this->getRelationship([
    //         'ids' => $metadetail->pages->lists('id'),
    //         'type' => 'pages',
    //         'parent_id' => $metadetail_id,
    //         'parent_type' => 'metadetails'
    //     ]);
    // }
    // /*
    //  * delete relationship
    //  */
    // public function deletePagesRelationships(Request $request, $metadetail_id){
    //     $data = json_decode($request->getContent(), true);
    //     $metadetail = $this->validateResourceExists(Metadetail::find($metadetail_id));
    //
    //     foreach($data['data'] as $relationship){
    //         $metadetail->pages()->detach($relationship['id']);
    //     }
    //
    //     return $this->response->noContent();
    // }

}
