<?php

class SearchTest extends TestCase
{
    /**
     * @test
     */
    public function get_search()
    {
        $response = $this->getClientResponse('search');
        // check for HTTP_OK
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        // Check if data is correctly formatted & everything is returned
        $this->fail('Get search returns searches!');
    }
    /**
     * @test
     */
    public function post_search()
    {
        $response = $this->getClientResponse('search');
        // check for HTTP_OK
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        // Check if data is correctly formatted & everything is returned
        $this->fail('Empty search responds with recent or suggested seraches!');
    }
}
