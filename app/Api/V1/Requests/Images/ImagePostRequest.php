<?php

namespace App\Api\V1\Requests\Images;

use Illuminate\Http\Request;
use App\Api\V1\Requests\Images\ImageRequest;

class ImagePostRequest extends ImageRequest
{
    /**
     * get original request
     *
     * @method __construct
     *
     * @param  Request $request
     */
    public function __construct(Request $request){
        // if file should be uploaded
        $file = $request->file('file');
        if($request->segment(2) !== NULL && $file === NULL){
            throw new \Dingo\Api\Exception\StoreResourceFailedException('A file must be provided.');
        }

        parent::__construct($request);
    }
    /**
     * The scopes needed to do this request
     *
     * @return array
     */
    protected function scopes(){
        return [

        ];
    }
    /**
     * validation rules
     *
     * @method rules
     *
     * @return array
     */
    protected function rules(){
        return [
            'type' => 'required|in:images',
            'attributes.link' => 'url|required',
            'attributes.slug' => 'string|required',
            'attributes.bytesize' => 'int|required',
            'attributes.width' => 'int|required',
            'attributes.height' => 'int|required',
        ];
    }
    /**
     * image validation rules
     *
     * @method fileRules
     *
     * @return array
     */
    protected function fileRules(){
        return [
            'file' => 'required|mimes:jpeg,png,bmp,svg,gif|max:5000',
        ];
    }
}
