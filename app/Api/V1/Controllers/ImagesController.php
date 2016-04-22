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
    /*
     * uplload image
     */
    public function upload(Request $request, $id)
    {
        $model = $this->newModel()->find($id);
        $file = $request->file('file');
        \LOG::debug('TEST');
        \LOG::debug($file);
        dd($file);

        $adapter = new SftpAdapter([
            'host' => 'ftp.formandsystem.com',
            'username' => '373917-test',
            'password' => 'test1234s',
            'ssl' => false,
            'timeout' => 30,
        ]);

        $filesystem = new Filesystem($adapter);

        if($filesystem->has('/images/'.$model->slug.'.'.$file->guessExtension())){
            throw new \Dingo\Api\Exception\StoreResourceFailedException('The file already exists.');
        }
        $filesystem->write('/images/'.$model->slug.'.'.$file->guessExtension(), $file);
    }
}
