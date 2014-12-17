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

      $content['sections'] = $this->inlineFragments($content['data'], $this->fragmentTransformer->transformArray($content['fragments']) );
      unset($content['data'], $content['fragments']);

      $item['content'][$key] = $content;
    }

    // if( isset($item['content']) )
    // {
    //   foreach($item['content'] as $lang => $content)
    //   {
    //     //TODO: clean up whole transformer class
    //     // remove some data from array
    //     unset($item['content'][$lang]['data'],
    //     $item['content'][$lang]['article_id']);
    //
    //     $item['content'][$lang]['sections'] = $content['data'];
    //
    //     if( is_array($content['tags']) )
    //     {
    //       $t = [];
    //       foreach($content['tags'] as $tag)
    //       {
    //         $t[] = $tag['name'];
    //       }
    //
    //       $item['content'][$lang]['tags'] = $t;
    //     }
    //   }
    //
    // }

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
    return $output;
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
      // \Log::warning($fragmentArray);

      foreach( $fragmentArray as $fragment )
      {
        $fragments[$fragment['fragment_id']] = $fragment;
      }

      foreach( $data as $key => $section)
      {
        foreach( $section['columns'] as $k => $column )
        {
          $data[$key]['columns'][$k]['fragment'] = $fragments[$column['fragment']];
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
      foreach( $array as $fragment)
      {
        $fragments[$fragment['id']] = $fragment;
      }
      return $fragments;
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
