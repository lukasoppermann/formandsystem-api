<?php

class ImageTest extends TestCase
{
    protected $resource = 'images';

    /**
     * @test upload image
     */
    public function uploadImage(){
        // PREPARE
        $model = $this->model->first();
        // POST
        $response = $this->client->request('POST', '/'.$this->resource.'/'.$model->id, [
            // 'headers' => ['Content-Type => multipart/form-data'],
            'multipart' => [['name' => 'file', 'contents' => file_get_contents('http://lorempixel.com/400/200/cats/')]]
        ]);
        // GET DATA
        $data = $this->getResponseArray($response)['data'];
        // ASSERTIONS
        $this->assertEquals(self::HTTP_CREATED, $response->getStatusCode());
        $this->assertNotNull($this->model->find($data['id']));
        $this->assertValid($data, $this->resource()->blueprint());
    }
}
