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
    return [
      'id'          => (int) $item['id'],
      'article_id'  => (int) $item['article_id'],
      'menu_label'  => $item['menu_label'],
      'link'        => $item['link'],
      'status'      => (int) $item['status'],
      'language'    => $item['language'],
      'data'        => json_decode($item['data'], true),
      'tags'        => array_map("trim", array_filter(explode(',', $item['tags']))),
      'created_at'  => $item['created_at'],
      'updated_at'  => $item['updated_at'],
      'deleted_at'  => $item['deleted_at']
    ];
  }

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
