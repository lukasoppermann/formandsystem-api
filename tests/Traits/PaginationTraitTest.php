<?php

use App\Api\V1\Traits\PaginationTrait;

class PaginationTraitTest extends TestCase{

    protected $request;
    protected $items;
    protected $faker;

    public function setUp()
    {
        $this->faker = Faker\Factory::create();
        $this->request = \Mockery::mock('App\Api\V1\Requests\AbstractRequest');
        $this->items = $this->faker->words(30);
    }

    public function testPaginate()
    {
        $this->request->shouldReceive('all')->andReturn();
        $this->request->shouldReceive('path')->andReturn('test');

        $trait = new traitClassStub();
        $paginated = $trait->paginate(collect($this->items), $this->request);
        $this->assertEquals(count($paginated->toArray()['data']), 20);
    }
}

class traitClassStub{
    use PaginationTrait;
}
