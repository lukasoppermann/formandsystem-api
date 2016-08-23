<?php

use Illuminate\Database\Eloquent\Model;

class AbstractRepositoryTest extends TestCase{

    protected $model;
    protected $stubRepository;

    public function setUp()
    {
        parent::setUp();
        $this->model = \Mockery::mock('App\Api\V1\Models\BaseModel');
    }

    public function testFindOrFailThrowsException()
    {
        $this->expectException(Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);

        $stub = $this->getMockForAbstractClass('App\Api\V1\Repositories\AbstractRepository');
        $this->model->shouldReceive('findOrFail')->with(NULL)->andThrow('Illuminate\Database\Eloquent\ModelNotFoundException');

        $stub->findOrFail($this->model, NULL);
    }

    public function testApplyFilter()
    {
        $this->expectException(Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);

        $stub = $this->getMockForAbstractClass('App\Api\V1\Repositories\AbstractRepository');
        $this->model->shouldReceive('findOrFail')->with(NULL)->andThrow('Illuminate\Database\Eloquent\ModelNotFoundException');

        $stub->findOrFail($this->model, NULL);
    }

}
