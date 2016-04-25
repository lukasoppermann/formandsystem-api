<?php

use League\Flysystem\Filesystem;
use League\Flysystem\Sftp\SftpAdapter;

class ImageTest extends TestCase
{
    /**
     * the type of resource this test deals with
     *
     * @var string
     */
    protected $resource = 'images';
    /**
     * test ftp data used to upload and verify files
     *
     * @var array
     */
    protected $testSftp = [
        'host' => 'ftp.formandsystem.com',
        'username' => '373917-test',
        'password' => 'test1234',
        'ssl' => false,
        'timeout' => 30,
    ];
    /**
     * the test files with content type to use for uploading
     *
     * @var array
     */
    protected $testFiles = [
        'image/png'     => "/tests/files/png-image.png",
        'image/gif'     => "/tests/files/gif-image.gif",
        'image/jpeg'    => "/tests/files/jpg-image.jpg",
        'image/svg+xml' => "/tests/files/svg-image.svg",
    ];
    /**
     * the invalid test files with content type to use for uploading
     *
     * @var array
     */
    protected $invalidTestFiles = [
        'image/png'     => "/tests/files/php-image.png",
        'image/png'     => "/tests/files/php-image.php",
        'image/png'     => "/tests/files/php-image2.png",
        'text/x-php'     => "/tests/files/php-image.php",
        'image/x-ms-bmp'     => "/tests/files/bmp-image.bmp",
    ];
    /**
     * @test upload image
     */
    public function uploadImage(){
        // Setup the FTP
        $adapter = new SftpAdapter($this->testSftp);
        // return the system
        $filesystem = new Filesystem($adapter);
        // Loop through all valida files
        foreach($this->testFiles as $contentType => $file){
            // remove link from item
            $model = $this->model->first();
            $model->link = NULL;
            $model->save();
            // POST
            $response = $this->client->request('POST', '/'.$this->resource.'/'.$model->id, [
                    'headers' => ['Content-Type' => $contentType],
                    'body' => fopen(base_path().$file,'r')
            ]);
            // GET DATA
            $data = $this->getResponseArray($response)['data'];
            // ASSERTIONS
            $this->assertEquals(self::HTTP_CREATED, $response->getStatusCode());
            $this->assertTrue($filesystem->has('/images/'.$data['attributes']['link']));
            $this->assertValid($data, $this->resource()->blueprint());
        }
    }
    /**
     * @test upload invalid image
     */
    public function uploadInvalidImage(){
        // Loop through all valida files
        foreach($this->invalidTestFiles as $contentType => $file){
            // remove link from item
            $model = $this->model->first();
            $model->link = NULL;
            $model->save();
            // POST
            $response = $this->client->request('POST', '/'.$this->resource.'/'.$model->id, [
                    'headers' => ['Content-Type' => $contentType],
                    'body' => fopen(base_path().$file,'r')
            ]);
            // ASSERTIONS
            $this->assertEquals(self::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        }
    }
}
