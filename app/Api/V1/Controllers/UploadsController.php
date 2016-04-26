<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Ftp as FtpAdapter;
use League\Flysystem\Sftp\SftpAdapter;


class ImagesController extends ApiController
{
    /**
     * allowed image mime types
     *
     * @var [array]
     */
    protected $mimeTypes = [
        'images' => [
            'image/png' => 'png',
            'image/jpeg' => 'jpg',
            'image/gif' => 'gif',
            'image/bmp' => 'bmp',
            'image/svg+xml' => 'svg'
        ]
    ];
    /*
     * upload image
     */
    public function upload(Request $request, $id)
    {
        // get resource type
        $resourceType = $this->getResourceType($request->header('Content-Type'));
        // validate that the requested resource exists
        $model = $this->validateResourceExists(
            $this->newModel(ucfirst(substr($resourceType,0,-1)))->find($id),
            'The upload link is invalid.'
        );
        // validate image can be uploaded
        $this->validateUploadable($model);
        // write file to remote disk
        \LOG::debug('missing credentials');
        $file = $this->newFilesystem([])->write('/images/'.$model->link, $request->getContent());
        // respond
        if($file === true){
            // return true
            return $this->response->item($model, $this->newTransformer(), ['key' => $this->resource])->setStatusCode(201);
        }
        // File could not be saved
        throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException('Failed storing image.');
    }
    /**
     * Get the kind of resource (image, video, file, etc) from mime type
     *
     * @method getResourceType
     *
     * @param  [string]            $providedMime
     *
     * @return [string]
     */
    protected function getResourceType($providedMime = NULL){
        foreach($this->mimeTypes as $type => $mimes){
            if(array_key_exists($providedMime, $mimes)){
                // get resource type
                return $type;
            }
        }
    }
    /**
     * Create a new Filesystem with the given credentials
     *
     * @method newFilesystem
     *
     * @param  [array]            $credentials
     *
     * @return [Filesystem]
     */
    protected function newFilesystem($credentials){
        // Setup the adapter
        $adapter = new SftpAdapter([
            'host' => 'ftp.formandsystem.com',
            'username' => '373917-test',
            'password' => 'test1234',
            'ssl' => false,
            'timeout' => 30,
        ]);
        // return the system
        return new Filesystem($adapter);
    }
    /**
     * validates the model is not a linked image
     *
     * @method validateUploadable
     *
     * @param  [Model]        $model
     *
     * @return [void|exception]
     */
    protected function validateUploadable($model){
        if($model->bytesize === NULL){
            throw new \Dingo\Api\Exception\StoreResourceFailedException('You cannot upload a file for an external resource.');
        }
    }
}
