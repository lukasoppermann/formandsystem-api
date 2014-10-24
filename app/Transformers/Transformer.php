<?php namespace Formandsystemapi\Transformers;

abstract class Transformer {

  /**
   * Transform an array of items
   *
   * @method transformArray
   *
   * @param  array $items
   */
  public function transformArray( array $items )
  {
    return array_map([$this, 'transform'], $items);
  }

  /**
   * transforms a single item
   *
   * @method transform
   *
   * @param  array $item
   *
   * @return array
   */
  public abstract function transform($item);

  /**
   * transforms a single item for inserting
   *
   * @method transformPost
   *
   * @param  array $item
   *
   * @return array
   */
  public abstract function transformPostData($data);

}
