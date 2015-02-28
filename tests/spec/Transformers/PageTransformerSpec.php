<?php namespace spec\Formandsystemapi\Transformers;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Formandsystemapi\Transformers\FragmentTransformer;

class PageTransformerSpec extends ObjectBehavior
{
    function let(FragmentTransformer $fragmentTransformer)
    {
        $this->beConstructedWith($fragmentTransformer);
    }

    function it_is_initializable()
    {
        $this->shouldBeAnInstanceOf('Formandsystemapi\Transformers\PageTransformer');
    }

    function it_extends_abstract_class_transformer()
    {
        $this->shouldBeAnInstanceOf('Formandsystemapi\Transformers\Transformer');
    }

    function it_converts_get_data_correctly( FragmentTransformer $fragmentTransformer )
    {
      $fragment = [[
        'fragment_id' => 1,
        'fragment_key' => NULL,
        'fragment_type' => 'text',
        'content' =>  array (
          'text' => 'Some random text',
        ),
        'created_at' => '-0001-11-30 00:00:00',
        'updated_at' => '-0001-11-30 00:00:00',
        'deleted_at' => NULL,
      ]];

      $fragmentTransformer->transformArray(Argument::any())->willReturn($fragment);

      $this->transform(
      // beginning of input
        array (
          'id' => 2,
          'parent_id' => 0,
          'article_id' => 1,
          'stream' => 'navigation',
          'position' => 1,
          'deleted_at' => NULL,
          'content' => array (
            0 => array (
              'id' => 1,
              'article_id' => 1,
              'menu_label' => 'Home',
              'link' => 'home',
              'published' => 1,
              'language' => 'de',
              'data' => array (
                0 => array (
                  'class' => 'section-01',
                  'columns' => array (
                    0 => array (
                      'fragment' => 1,
                      'columns' => 4,
                      'offset' => 0,
                    ),
                    1 => array (
                      'fragment' => 1,
                      'columns' => 6,
                      'offset' => 2,
                    )
                  )
                ),
                1 => array (
                  'class' => 'space-bottom-wide',
                  'link' => 'Vision',
                  'columns' => array (
                    0 => array (
                      'fragment' => 1,
                      'columns' => 4,
                      'offset' => 2,
                    ),
                  )
                )
              ),
              'created_at' => '2014-12-16 02:53:57',
              'updated_at' => '2014-12-16 02:53:57',
              'deleted_at' => NULL,
              'tags' => array (
                0 => array (
                  'id' => 1,
                  'name' => 'vel',
                  'internal' => NULL,
                  'pivot' => array (
                    'content_id' => 1,
                    'tag_id' => 1,
                  ),
                ),
                1 => array (
                  'id' => 2,
                  'name' => 'optio',
                  'internal' => NULL,
                  'pivot' => array (
                    'content_id' => 1,
                    'tag_id' => 2,
                  ),
                ),
                2 => array (
                  'id' => 3,
                  'name' => 'aliquam',
                  'internal' => NULL,
                  'pivot' => array (
                    'content_id' => 1,
                    'tag_id' => 3,
                  ),
                ),
              ),
              'fragments' => array (
                0 => array (
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
                ),
              ),
            ),
          ),
        )
      // end of input
      )->shouldReturn(
      // beginning of output
        array (
          'article_id' => 1,
          'stream_record_id' => 2,
          'stream' => 'navigation',
          'position' => 1,
          'parent_id' => 0,
          'content' => array (
            'de' => array (
              'id' => 1,
              'article_id' => 1,
              'menu_label' => 'Home',
              'link' => 'home',
              'published' => 1,
              'language' => 'de',
              'created_at' => '2014-12-16 02:53:57',
              'updated_at' => '2014-12-16 02:53:57',
              'deleted_at' => NULL,
              'tags' => ['vel','optio','aliquam'],
              'sections' => array (
                0 => array (
                  'class' => 'section-01',
                  'columns' => array (
                    0 => array (
                      'fragment' => $fragment[0],
                      'columns' => 4,
                      'offset' => 0,
                    ),
                    1 => array (
                      'fragment' => $fragment[0],
                      'columns' => 6,
                      'offset' => 2,
                    )
                  )
                ),
                1 => array (
                  'class' => 'space-bottom-wide',
                  'link' => 'Vision',
                  'columns' => array (
                    0 => array (
                      'fragment' => $fragment[0],
                      'columns' => 4,
                      'offset' => 2,
                    ),
                  )
                )
              ),
              ),
            ),
        )
      // end of output
      );
    }


    function it_converts_post_data_correctly()
    {
      $this->transformPostData(
        [
          'article_id'  => "1",
          'menu_label'  => "label",
          'link'        => "page/test",
          'status'      => "1",
          'language'    => "de",
          'data'        => ["test" => "test"],
          'tags'        => ["test", "test 2" ],
        ]
      )->shouldReturn(
        [
          'article_id'  => "1",
          'menu_label'  => "label",
          'link'        => "page/test",
          'status'      => "1",
          'language'    => "de",
          'data'        => '{"test":"test"}',
          'tags'        => "test, test 2",
        ]);
    }

    function it_converts_empty_fields_correctly( )
    {
      // no data
      $this->transform(
      // beginning of input
        array (
          'id' => 2,
          'parent_id' => 0,
          'article_id' => 1,
          'stream' => 'navigation',
          'position' => 1,
          'deleted_at' => NULL,
          'content' => array (
            0 => array (
              'id' => 1,
              'article_id' => 1,
              'menu_label' => 'Home',
              'link' => 'home',
              'published' => 1,
              'language' => 'de',
              'data' => [],
              'created_at' => '2014-12-16 02:53:57',
              'updated_at' => '2014-12-16 02:53:57',
              'deleted_at' => NULL,
              'tags' => [],
              'fragments' => []
            ),
          ),
        )
      // end of input
      )->shouldReturn(
      // beginning of output
        array (
          'article_id' => 1,
          'stream_record_id' => 2,
          'stream' => 'navigation',
          'position' => 1,
          'parent_id' => 0,
          'content' => array (
            'de' => array (
              'id' => 1,
              'article_id' => 1,
              'menu_label' => 'Home',
              'link' => 'home',
              'published' => 1,
              'language' => 'de',
              'created_at' => '2014-12-16 02:53:57',
              'updated_at' => '2014-12-16 02:53:57',
              'deleted_at' => NULL,
              'tags' => [],
              'sections' => [],
              ),
            ),
        )
      // end of output
      );
      // no fragments
      $this->transform(
      // beginning of input
        array (
          'id' => 2,
          'parent_id' => 0,
          'article_id' => 1,
          'stream' => 'navigation',
          'position' => 1,
          'deleted_at' => NULL,
          'content' => array (
            0 => array (
              'id' => 1,
              'article_id' => 1,
              'menu_label' => 'Home',
              'link' => 'home',
              'published' => 1,
              'language' => 'de',
              'data' => array (
                0 => array (
                  'class' => 'section-01',
                  'columns' => array (
                    0 => array (
                      'fragment' => 1,
                      'columns' => 4,
                      'offset' => 0,
                    ),
                    1 => array (
                      'fragment' => 1,
                      'columns' => 6,
                      'offset' => 2,
                    )
                  )
                ),
                1 => array (
                  'class' => 'space-bottom-wide',
                  'link' => 'Vision',
                  'columns' => array (
                    0 => array (
                      'fragment' => 1,
                      'columns' => 4,
                      'offset' => 2,
                    ),
                  )
                )
              ),
              'created_at' => '2014-12-16 02:53:57',
              'updated_at' => '2014-12-16 02:53:57',
              'deleted_at' => NULL,
              'tags' => [],
              'fragments' => []
            ),
          ),
        )
      // end of input
      )->shouldReturn(
      // beginning of output
        array (
          'article_id' => 1,
          'stream_record_id' => 2,
          'stream' => 'navigation',
          'position' => 1,
          'parent_id' => 0,
          'content' => array (
            'de' => array (
              'id' => 1,
              'article_id' => 1,
              'menu_label' => 'Home',
              'link' => 'home',
              'published' => 1,
              'language' => 'de',
              'created_at' => '2014-12-16 02:53:57',
              'updated_at' => '2014-12-16 02:53:57',
              'deleted_at' => NULL,
              'tags' => [],
              'sections' => [],
              ),
            ),
        )
      // end of output
      );
    }


}
