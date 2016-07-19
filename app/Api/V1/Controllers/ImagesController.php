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

    public function delete($resource_id){
        $model = $this->newModel()->findWithTrashed($resource_id);
        try{
            $this->newFilesystem(app('config')->get('user.ftp.image'))->delete('/'.$model->filename);
        }catch(\Exception $e){
            \Log::error($e);
        }
        return parent::delete($resource_id);
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
        $adapter = new SftpAdapter(array_merge([
            'timeout' => 30,
        ],$credentials));
        // return the system
        return new Filesystem($adapter);
    }
}
