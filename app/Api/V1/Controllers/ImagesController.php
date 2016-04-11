<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Models\Image;
use App\Api\V1\Transformers\ImageTransformer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ImagesController extends ApiController
{
    /**
     * The filters that are allowed in requests
     *
     * @var array
     */
    protected $resource = 'images';
    /*
     * get related
     */
    public function getFragments(Request $request, $image_id){
        $image = $this->validateResourceExists(Image::find($image_id));
        // return related objects
        return $this->getRelated($request,
            $image->fragments(),
            'fragments'
        );

    }
    /*
     * get relationship
     */
    public function getFragmentsRelationships(Request $request, $image_id){
        $image = $this->validateResourceExists(Image::find($image_id));
        // return relationship
        return $this->getRelationship([
            'ids' => $image->fragments->lists('id'),
            'type' => 'fragments',
            'parent_id' => $image_id,
            'parent_type' => 'images'
        ]);

    }


}
