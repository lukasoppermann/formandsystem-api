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
  // function it_executes_transform_on_the_array(){
  //
  //   $this->transform(['test'])->shouldBeCalled();
  //
  //   $this->transformArray(['test', 'test']);
  //
  // }

}

class DummyTransformer extends \Formandsystemapi\Transformers\Transformer{

  public function transform( $item )
  {
    return null;
  }

}
