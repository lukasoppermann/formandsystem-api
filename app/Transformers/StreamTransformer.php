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

    return [
      'stream'            => isset($data['stream']) ? $data['stream'] : null,
      'position'          => isset($data['position']) ? (int) $data['position'] : 0,
      'parent_id'         => isset($data['parent_id']) ? (int) $data['parent_id'] : 0
    ];

  }


}
