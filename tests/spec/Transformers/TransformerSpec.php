<?php

namespace spec\Formandsystemapi\Transformers;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TransformerSpec extends ObjectBehavior
{
  function let(){
    $this->beAnInstanceOf('spec\Formandsystemapi\Transformers\DummyTransformer');
  }
  //
  function it_executes_transform_on_the_array(){

    $this->transformArray(['test', 'test2'])->shouldReturn(['test-tested', 'test2-tested']);

  }

}

class DummyTransformer extends \Formandsystemapi\Transformers\Transformer{

  public function transform( $item )
  {
    return $item.'-tested';
  }

}
