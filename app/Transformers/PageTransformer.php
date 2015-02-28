<?php namespace Formandsystemapi\Transformers;

use Formandsystemapi\Transformers\FragmentTransformer as FragmentTransformer;

class PageTransformer extends Transformer{

  protected $fragmentTransformer;

  function __construct(FragmentTransformer $fragmentTransformer)
  {
    $this->fragmentTransformer = $fragmentTransformer;
  }

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
    foreach($item['content'] as $key => $content)
    {
      $content['tags'] = $this->inlineTags($content['tags']);

      // inline Fragmenrs into sections
      $content['sections'] = $this->inlineFragments($content['data'], $this->fragmentTransformer->transformArray($content['fragments']) );
      unset($content['data'], $content['fragments']);

      // unset itself because and use lang as key
      unset($item['content'][$key]);
      $item['content'][$content['language']] = $content;
    }

    return [
      'article_id'        => (int) $item['article_id'],
      'stream_record_id'  => isset($item['id']) ? (int) $item['id'] : NULL,
      'stream'            => isset($item['stream']) ? $item['stream'] : null,
      'position'          => isset($item['position']) ? (int) $item['position'] : 0,
      'parent_id'         => isset($item['parent_id']) ? (int) $item['parent_id'] : 0,
      'content'           => isset($item['content']) ? $item['content'] : [],
    ];
  }

  /**
   * transform merged tags into one name-only array
   *
   * @method inlineTags
   *
   * @param  array    $tags
   */
  private function inlineTags( $tags )
  {
    foreach( $tags as $tag)
    {
      $output[] = $tag['name'];
    }
    return isset($output) ? $output : [];
  }

  /**
   * transform merged fragments & data into sections array
   *
   * @method inlineFragments
   *
   * @param  array $data
   * @param  array $fragments
   */
  private function inlineFragments( $data, $fragmentArray )
  {
    $fragments = $this->sortByFragmentId($fragmentArray);

    foreach( $data as $key => $section)
    {
      if( isset($section['columns']) )
      {
        foreach( $section['columns'] as $k => $column )
        {
          if( isset($fragments[$column['fragment']]) )
          {
            $data[$key]['columns'][$k]['fragment'] = $fragments[$column['fragment']];
          }
          else
          {
            unset($data[$key]);
          }
        }
      }
    }
    return $data;
  }

  /**
   * sort fragments by their id
   *
   * @method sortByFragmentId
   *
   * @param  array    $fragments
   */
  private function sortByFragmentId( $array )
  {
    if( !isset($array) || !is_array($array) )
    {
      return false;
    }

    foreach( $array as $fragment)
    {
      $fragments[$fragment['fragment_id']] = $fragment;
    }
    return isset($fragments) ? $fragments : [];
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
