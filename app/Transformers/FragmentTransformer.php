<?php namespace Formandsystemapi\Transformers;

class FragmentTransformer extends Transformer{

  public function transform( $fragment )
  {
    return [
      'fragment_id'   => $fragment['id'],
      'fragment_key'  => $fragment['key'],
      'fragment_type' => $fragment['data']['type'],
      'content'       => $fragment['data']['content'],
      'created_at'    => $fragment['created_at'],
      'updated_at'    => $fragment['updated_at'],
      'deleted_at'    => $fragment['deleted_at'],
    ];
  }

}
