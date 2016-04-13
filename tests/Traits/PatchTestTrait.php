<?php

namespace Lukasoppermann\Testing\Traits;


trait PatchTestTrait
{
    /*
     * patch the main resource by id
     */
    public function patchResourceById(){
        // PREPARE
        $model = $this->model->first();
        // PATCH
        $response = $this->client->request('PATCH', '/'.$this->resource.'/'.$model->id, [
            'headers' => ['Accept' => 'application/json'],
            'body' => json_encode([
                "data" => array_merge(
                    $this->resources[$this->resource.'_post_incomplete'],
                    ['id' => $model->id]
                )
            ])
        ]);
        // ASSERTIONS
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        $this->assertValidArray($this->resources[$this->resource], json_decode($response->getBody(), true)['data']);
    }
    /*
     * patch the main resource by wrong id
     */
    public function patchResourceWrongId(){
        // PATCH
        $response = $this->client->request('PATCH', '/'.$this->resource.'/1', [
            'headers' => ['Accept' => 'application/json'],
            'body' => json_encode([
                "data" => array_merge(
                    $this->resources[$this->resource.'_post_incomplete'],
                    ['id' => $this->model->first()->id]
                )
            ])
        ]);
        // ASSERTIONS
        $this->assertEquals(self::HTTP_NOT_FOUND, $response->getStatusCode());
    }
    /*
     * patch the main resource by id without attributes
     */
    public function patchResourceByIdWithoutAttributes(){
        // PATCH
        $response = $this->client->request('PATCH', '/'.$this->resource.'/'.$this->model->first()->id, [
            'headers' => ['Accept' => 'application/json'],
            'body' => json_encode([
                "data" => [
                    'id' => $this->model->first()->id,
                    'type' => $this->resource
                ]
            ])
        ]);
        // ASSERTIONS
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
    }
    /*
     * patch the main resource by id without body
     */
    public function patchResourceByIdNoBody(){
        // PATCH
        $response = $this->client->request('PATCH', '/'.$this->resource.'/'.$this->model->first()->id, [
            'headers' => ['Accept' => 'application/json']
        ]);
        // ASSERTIONS
        $this->assertEquals(self::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
    /*
     * patch the main resource by id with wrong body
     */
    public function patchResourceIncompleteData(){
        // PATCH
        $response = $this->client->request('PATCH', '/'.$this->resource.'/'.$this->model->first()->id, [
            'headers' => ['Accept' => 'application/json'],
            'body' => json_encode([
                "data" => array_merge(
                    $this->resources[$this->resource.'_post_incomplete']
                )
            ])
        ]);
        // ASSERTIONS
        $this->assertEquals(self::HTTP_BAD_REQUEST, $response->getStatusCode());
        // PREPARE FOR NO TYPE
        $data = array_merge(
            $this->resources[$this->resource.'_post_incomplete'],
            ['id' => $this->model->first()->id]
        );
        unset($data['type']);
        // PATCH
        $response = $this->client->request('PATCH', '/'.$this->resource.'/'.$this->model->first()->id, [
            'headers' => ['Accept' => 'application/json'],
            'body' => json_encode([
                "data" => $data
            ])
        ]);
        // ASSERTIONS
        $this->assertEquals(self::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
// END OF FILE
}
