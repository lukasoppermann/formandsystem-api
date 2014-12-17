<?php

namespace spec\Formandsystemapi\Transformers;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FragmentTransformerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Formandsystemapi\Transformers\FragmentTransformer');
    }

    function it_extends_abstract_class_transformer()
    {
        $this->shouldBeAnInstanceOf('Formandsystemapi\Transformers\Transformer');
    }

    function it_converts_get_data_correctly()
    {
      $this->transform(
      // beginning of input
        array (
          'id' => 1,
          'key' => NULL,
          'data' => array (
            'type' => 'text',
            'content' =>  array (
              'text' => 'Some random text',
            ),
          ),
          'created_at' => '-0001-11-30 00:00:00',
          'updated_at' => '-0001-11-30 00:00:00',
          'deleted_at' => NULL,
          'pivot' => array (
            'content_id' => 1,
            ' fragment_id' => 1,
          ),
        )
      // end of input
      )->shouldReturn(
      // beginning of output
        array (
          'fragment_id' => 1,
          'fragment_key' => NULL,
          'fragment_type' => 'text',
          'content' =>  array (
            'text' => 'Some random text',
          ),
          'created_at' => '-0001-11-30 00:00:00',
          'updated_at' => '-0001-11-30 00:00:00',
          'deleted_at' => NULL,
        )
      // end of output
      );
    }
}
