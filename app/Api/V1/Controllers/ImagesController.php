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

}
