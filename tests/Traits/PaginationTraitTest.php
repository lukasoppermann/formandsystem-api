<?php

use App\Api\V1\Traits\PaginationTrait;

class PaginationTraitTest extends TestCase{

    protected $request;
    protected $items;
    protected $faker;
    protected $trait;

    public function setUp()
    {
        $this->faker = Faker\Factory::create();
        $this->request = \Mockery::mock('App\Api\V1\Requests\AbstractRequest');
        $this->items = $this->faker->words(30);
        $this->trait = new traitClassStub();
    }

    public function testPaginate()
    {
        $this->request->shouldReceive('all');
        $this->request->shouldReceive('path')->andReturn('test');

        $paginated = $this->trait->paginate(collect($this->items), $this->request);
        $this->assertEquals(count($paginated->toArray()['data']), 20);
    }

    public function testPaginateCustomPerPage()
    {
        $this->request->shouldReceive('all');
        $this->request->shouldReceive('path')->andReturn('test');

        $perPage = 3;
        $paginated = $this->trait->paginate(collect($this->items), $this->request, $perPage);
        $this->assertEquals($paginated->toArray()['last_page'], count($this->items)/$perPage);
    }

    public function testPaginatePathAndQuery()
    {
        $this->request->shouldReceive('all')->andReturn([
            'filter' => [
                'test' => 'test'
            ]
        ]);
        $this->request->shouldReceive('path')->andReturn('test');

        $paginated = $this->trait->paginate(collect($this->items), $this->request);
        $this->assertEquals($paginated->toArray()['next_page_url'], '/test?filter%5Btest%5D=test&page=2');
    }
}

// stub class
class traitClassStub{
    use PaginationTrait;
}
