<?php

namespace spec\Formandsystemapi\Repositories\Stream;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EloquentStreamRepositorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Formandsystemapi\Repositories\Stream\EloquentStreamRepository');
    }

    function it_implements_Interface()
    {
        $this->shouldImplement('Formandsystemapi\Repositories\Stream\StreamRepositoryInterface');
    }
}
