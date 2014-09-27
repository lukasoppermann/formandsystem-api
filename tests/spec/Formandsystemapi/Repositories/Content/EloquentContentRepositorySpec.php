<?php

namespace spec\Formandsystemapi\Repositories\Content;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EloquentContentRepositorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Formandsystemapi\Repositories\Content\EloquentContentRepository');
    }

    function it_implements_Interface()
    {
        $this->shouldImplement('Formandsystemapi\Repositories\Content\ContentRepositoryInterface');
    }
}
