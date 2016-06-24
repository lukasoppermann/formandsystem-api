<?php

use League\Flysystem\Filesystem;
use League\Flysystem\Sftp\SftpAdapter;

class ImageTest extends ResourceTestCase
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
        'text/x-php'     => "/tests/files/php-image.php",
        'image/x-ms-bmp'     => "/tests/files/bmp-image.bmp",
    ];
    /**
     * upload image
     *
     * @group upload
     */
    public function testPostResource(){
        // Setup the FTP
        // $adapter = new SftpAdapter($this->testSftp);
        // return the system
        // $filesystem = new Filesystem($adapter);
        // Loop through all valida files
        foreach($this->testFiles as $contentType => $file){
            // POST
            $response = $this->client()->request('POST', '/'.$this->resource, [
                'headers' => $this->headers(),
                'body' => json_encode([
                    "data" => $this->resource()->data(substr($file,-3))
                ])
            ]);
            // GET DATA
            $data = $this->getResponseArray($response)['data'];
            // ASSERTIONS
            $this->assertEquals(self::HTTP_CREATED, $response->getStatusCode());
            $this->assertNotNull($this->model->find($data['id']));
            $this->assertValid($data, $this->resource()->blueprint());
            // Upload image
            $response = $this->client()->request('PUT', $data['links']['upload'], [
                'headers' => array_merge(
                    $this->headers(),
                    ['Content-Type' => $contentType]
                ),
                'body' => fopen(base_path().$file,'r')
            ]);
            // // GET DATA
            $data = $this->getResponseArray($response)['data'];
            // // ASSERTIONS
            $this->assertEquals(self::HTTP_CREATED, $response->getStatusCode());
            // // $this->assertTrue($filesystem->has('/images/'.$data['attributes']['link']));
            $this->assertValid($data, $this->resource()->blueprint());
        }
    }
    /**
     * @test upload invalid image
     */
    public function uploadInvalidImage(){
        // Loop through all valida files
        foreach($this->invalidTestFiles as $contentType => $file){
            // POST
            $response = $this->client()->request('POST', '/'.$this->resource, [
                'headers' => $this->headers(),
                'body' => json_encode([
                    "data" => $this->resource()->data(substr($file,-3))
                ])
            ]);
            // GET DATA
            $data = $this->getResponseArray($response)['data'];
            // ASSERTIONS
            $this->assertEquals(self::HTTP_CREATED, $response->getStatusCode());
            $this->assertNotNull($this->model->find($data['id']));
            $this->assertValid($data, $this->resource()->blueprint());
            // POST
            $response = $this->client()->request('PUT', $data['links']['upload'], [
                    'headers' => ['Content-Type' => $contentType],
                    'body' => fopen(base_path().$file,'r')
            ]);
            // ASSERTIONS
            $this->assertEquals(self::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        }
    }

}
