<?php namespace spec\Formandsystemapi\Repositories\Stream;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Formandsystemapi\Models\Stream;

class EloquentStreamRepositorySpec extends ObjectBehavior
{

    function let(Stream $model)
    {
        $this->beConstructedWith($model);
    }
    function it_is_initializable()
    {
        $this->shouldHaveType('Formandsystemapi\Repositories\Stream\EloquentStreamRepository');
    }

    function it_implements_Interface_StreamRepositoryInterface()
    {
        $this->shouldImplement('Formandsystemapi\Repositories\Stream\StreamRepositoryInterface');
    }
}
