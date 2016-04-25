<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Models\Image;
use App\Api\V1\Transformers\ImageTransformer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Ftp as FtpAdapter;
use League\Flysystem\Sftp\SftpAdapter;


class ImagesController extends ApiController
{
    /**
     * The relationships a resource can have
     *
     * @var array
     */
    protected $relationships = [
        'images',
        'fragments',
    ];
    /**
     * The filters that are allowed in requests
     *
     * @var array
     */
    protected $availableFilters = [
        'slug'
    ];
    /**
     * The resources name
     *
     * @var array
     */
    protected $resource = 'images';
    /**
     * allowed image mime types
     *
     * @var [array]
     */
    protected $mimeTypes = [
        'image/png' => 'png',
        'image/jpeg' => 'jpg',
        'image/gif' => 'gif',
        'image/bmp' => 'bmp',
        'image/svg+xml' => 'svg'
    ];
    /*
     * upload image
     */
    public function upload(Request $request, $id)
    {
        // validate that the requested resource exists
        $model = $this->validateResourceExists(
            $this->newModel()->find($id),
            'The resource of type "'.$this->resource.'" with the id of "'.$id.'" does not exist.'
        );
        // validate image can be uploaded
        $this->validateUploadable($model);
        // Get the actual file content
        $file = $request->getContent();
        // get extension
        $ext = $this->mimeTypes[$request->header('Content-Type')];
        // get FileSystem
        $filesystem = $this->makeFilesystem([]);
        // build file name
        $filename = $model->slug.'.'.$ext;
        // check if image exists
        if($filesystem->has('/images/'.$filename)){
            throw new \Dingo\Api\Exception\StoreResourceFailedException('The file already exists.');
        }
        // write file to remote disk
        $file = $filesystem->write('/images/'.$filename, $file);
        // respond
        if($file === true){
            // update model with link
            $model->link = $filename;
            $model->save();
            // return true
            return $this->response->item($model, $this->newTransformer(), ['key' => $this->resource])->setStatusCode(201);
        }
        // File could not be saved
        throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException('Faild storing image.');
    }
    /**
     * Create a new Filesystem with the given credentials
     *
     * @method makeFtpFilesystem
     *
     * @param  [array]            $credentials
     *
     * @return [Filesystem]
     */
    protected function makeFilesystem($credentials){
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
        if($model->link !== NULL){
            throw new \Dingo\Api\Exception\StoreResourceFailedException('The image already exists.');
        }
    }
}
