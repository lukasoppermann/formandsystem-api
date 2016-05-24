<?php

namespace App\Api\V1\Requests;

use App\Api\V1\Requests\AbstractResourceRequest;

class UploadRequest extends AbstractResourceRequest
{
    use RequestAuthorization;
    /**
     * make this a file request
     *
     * @var [bool]
     */
    protected $fileRequest = TRUE;
    /**
     * determins if the user needs to have an image ftp access
     *
     * @var bool
     */
    protected $needs_ftp_image = TRUE;
    /**
     * allowed image mime types
     *
     * @var [array]
     */
    protected $mimeTypes = [
        'image/png' => 'png',
        'image/jpeg' => 'jpg',
        'image/gif' => 'gif',
        'image/svg+xml' => 'svg'
    ];
    /**
     * allowed max image size in bytes
     * @usage megabyte number * 1024 * 1024 = bytes
     * @var [int]
     */
    protected $byteSize = 5 * 1024 * 1024;
    /**
     * get original request
     *
     * @method __construct
     *
     * @param  Request $request
     */
    public function __construct($request){
        \LOG::debug('change upload to type FileRequest');
        // store current request
        $this->request = $request;
        // if file should be uploaded
        if($request->segment(2) !== NULL){
            $this->validateImage();
        }
        // not uploading image
        else {
            parent::__construct($request);
        }
    }
    /**
     * check if content type is allowd
     *
     * @method validateImageContentType
     *
     * @param [Request]     $request
     *
     * @return [void|exception]
     */
    protected function validateImage(){
        // validate Content-Type and Mime-Type
        $this->validateMimeType();
        // validate size of image
        $this->validateImageSize();
    }
    /**
     * validate image type matches image and is supported
     *
     * @method validateMimeType
     *
     * @return [void|Exception]
     */
    protected function validateMimeType(){
        // get content mime type
        $fileMime = (new \finfo(FILEINFO_MIME_TYPE))->buffer($this->request->getContent());
        $headerMime = $this->request->header('Content-Type');
        // test mime types
        if($fileMime !== $headerMime){
            throw new \Dingo\Api\Exception\StoreResourceFailedException('The content type specified in the header does not match the files content type.');
        }
        // test mime types
        if(!array_key_exists($headerMime, $this->mimeTypes)){
            throw new \Dingo\Api\Exception\StoreResourceFailedException('The image type you are trying to upload is not supported.');
        }
    }
    /**
     * test if image exceeds limit
     *
     * @method validateImageSize
     *
     * @return [void|Exception]
     */
    protected function validateImageSize(){
        // get image bytesize
        $imageByteSize = fstat($this->request->getContent(true))['size'];
        // throw exception if file to big
        if($imageByteSize > $this->byteSize){
            throw new \Dingo\Api\Exception\StoreResourceFailedException('The image you are trying to upload exceeds the limit of '.number_format($this->byteSize / 1048576, 2).' MB.');
        }
    }
}
