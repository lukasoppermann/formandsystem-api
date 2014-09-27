<?php

namespace spec\Formandsystemapi\Repositories;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EloquentAbstractRepositorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Formandsystemapi\Repositories\EloquentAbstractRepository');
    }

    function it_implements_Interface()
    {
        $this->shouldImplement('Formandsystemapi\Repositories\AbstractRepositoryInterface');
    }
}
