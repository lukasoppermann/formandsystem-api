<?php

class Metadetail_deleteTest extends TestCase
{
    /**
     * @test
     */
    public function delete_metadetail()
    {
        $id = App\Api\V1\Models\Metadetail::first()->id;
        $response = $this->client->request('DELETE', '/metadetails/'.$id, [
            'headers' => [
                'Accept' => 'application/json',
            ]
        ]);
        // check status code & response body
        $this->assertEquals(self::HTTP_NO_CONTENT, $response->getStatusCode());
        $this->assertNull(App\Api\V1\Models\Metadetail::find($id));
    }
    /**
     * @test
     */
    public function delete_metadetail_wrong_id()
    {
        $response = $this->client->request('DELETE', '/metadetails/1', [
            'headers' => [
                'Accept' => 'application/json',
            ]
        ]);
        // check status code & response body
        $this->assertEquals(self::HTTP_NO_CONTENT, $response->getStatusCode());
    }
    /**
     * @test
     */
    public function delete_metadetail_relationship()
    {
        $id = App\Api\V1\Models\Metadetail::all()->first(function($key, $item){
            return count($item->pages) > 0;
        })->id;
        $model = App\Api\V1\Models\Metadetail::find($id);
        $relationshipId = $model->pages()->first()->id;
        $response = $this->client->request('DELETE', '/metadetails/'.$id.'/relationships/pages', [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'body' => json_encode(["data" => [[
                "type" => "pages",
                "id" => $relationshipId
            ]]])
        ]);

        // check status code & response body
        $this->assertEquals(self::HTTP_NO_CONTENT, $response->getStatusCode());
        $this->assertEquals(count($model->pages()->find($relationshipId)),0);
    }
    /**
     * @test
     */
    public function delete_metadetail_relationship_wrong_id()
    {
        $id = App\Api\V1\Models\Metadetail::all()->first(function($key, $item){
            return count($item->pages) > 0;
        })->id;

        $response = $this->client->request('DELETE', '/metadetails/'.$id.'/relationships/pages', [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'body' => json_encode(["data" => [[
                "type" => "pages",
                "id" => '1234'
            ]]])
        ]);

        // check status code & response body
        $this->assertEquals(self::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
