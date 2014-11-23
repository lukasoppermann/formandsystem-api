<?php namespace Formandsystemapi\Transformers;

class PageTransformer extends Transformer{

  /**
   * transform data
   *
   * @method transform
   *
   * @param  array $item
   *
   * @return array
   */
  public function transform($item)
  {

    if( isset($item['content']) )
    {
      foreach($item['content'] as $key => $content)
      {
        $item['content'][$key]['data'] = json_decode($content['data'], true);


        if( is_array($content['tags']) )
        {
          $t = [];
          foreach($content['tags'] as $tag)
          {
            $t[] = $tag['name'];
          }

          $item['content'][$key]['tags'] = $t;
        }
      }

    }

    return [
      'article_id'        => (int) $item['article_id'],
      'stream_record_id'  => isset($item['id']) ? (int) $item['id'] : NULL,
      'stream'            => isset($item['stream']) ? $item['stream'] : null,
      'position'          => isset($item['position']) ? (int) $item['position'] : 0,
      'parent_id'         => isset($item['parent_id']) ? (int) $item['parent_id'] : 0,
      'content'           => isset($item['content']) ? $item['content'] : []
    ];
  }

  /**
   * transform Post data
   *
   * @method transformPostData
   *
   * @param  array $data
   */
  public function transformPostData($data)
  {
    // assign names
    $names = [
      'status' => 'status',
      'language' => 'language',
      'article_id' => 'article_id',
      'data' => 'data',
      'tags' => 'tags',
      'menu_label' => 'menu_label',
      'link' => 'link',
      'parent_id' => 'parent_id',
      'position' => 'position'
    ];

    // transform given data
    foreach( array_filter( $data ) as $key => $value )
    {
      $output[$names[$key]] = $value;
    }

    // transform tags
    if( isset($output['tags']) )
    {
      $output['tags'] = implode(', ',$output['tags']);
    }

    // transform data
    if( isset($output['data']) )
    {
      $output['data'] = json_encode($output['data']);
    }

    return $output;
  }


}
