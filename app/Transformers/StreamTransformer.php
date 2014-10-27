<?php namespace Formandsystemapi\Transformers;

class StreamTransformer extends Transformer{

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

    ];
  }

  public function transformPostData($data)
  {
    // assign names
    $names = [
      'language'    => 'language',
      'parent_id'   => 'parent_id',
      'article_id'  => 'article_id',
      'stream'      => 'stream',
      'position'    => 'position'
    ];

    // transform given data
    foreach( array_filter( $data ) as $key => $value )
    {
      $output[$names[$key]] = $value;
    }

    return $output;
  }


}
