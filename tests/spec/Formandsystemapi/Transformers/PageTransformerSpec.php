<?php namespace spec\Formandsystemapi\Transformers;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PageTransformerSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldBeAnInstanceOf('Formandsystemapi\Transformers\PageTransformer');
    }

    function it_extends_abstract_class_transformer()
    {
        $this->shouldBeAnInstanceOf('Formandsystemapi\Transformers\Transformer');
    }

    function it_converts_get_data_correctly()
    {
      $this->transform(
        [
          'wrong_param' => 'should be deleted',
          'id'          => "1",
          'article_id'  => "1",
          'menu_label'  => "label",
          'link'        => "page/test",
          'published'      => "1",
          'language'    => "de",
          'data'        => '{"test" : "test"}',
          'tags'        => [
            "id" => 7,
            "name" => "quasi",
            "internal" => null,
            "pivot" => [
              "content_id" => 1,
              "tag_id" => 7
            ]
          ],
          [
            "id" => 7,
            "name" => "quasi",
            "internal" => null,
            "pivot" => [
              "content_id" => 1,
              "tag_id" => 7
            ]
          ],
          'created_at'  => "2014-09-30 02:15:35",
          'updated_at'  => "2014-09-30 02:15:35",
          'deleted_at'  => "NULL"
        ]
      )->shouldReturn(
        [
          'id'          => 1,
          'article_id'  => 1,
          'menu_label'  => "label",
          'link'        => "page/test",
          'published'      => 1,
          'language'    => "de",
          'data'        => [ "test" => "test" ],
          'tags'        => ["test", "test 2" ],
          // 'created_at'  => "2014-09-30 02:15:35",
          // 'updated_at'  => "2014-09-30 02:15:35",
          // 'deleted_at'  => "NULL"
        ]);
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


}
