<?php

namespace spec\Formandsystemapi\Repositories\Content;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ContentRepositoryInterfaceSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Formandsystemapi\Repositories\Content\ContentRepositoryInterface');
    }
}
