<?php namespace spec\Formandsystemapi\Repositories\Content;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Formandsystemapi\Models\Content;

class EloquentContentRepositorySpec extends ObjectBehavior
{

    function let(Content $model)
    {
        $this->beConstructedWith($model);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Formandsystemapi\Repositories\Content\EloquentContentRepository');
    }

    function it_implements_Interface_ContentRepositoryInterface()
    {
        $this->shouldImplement('Formandsystemapi\Repositories\Content\ContentRepositoryInterface');
    }
}
