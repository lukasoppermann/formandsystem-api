<?php namespace Formandsystemapi\Transformers;

class PageTransformer extends Transformer{

  public function transform($item)
  {
    return [
      'id'          => (int) $item['id'],
      'article_id'  => (int) $item['article_id'],
      'menu_label'  => $item['menu_label'],
      'link'        => $item['link'],
      'status'      => (int) $item['status'],
      'language'    => $item['language'],
      'data'        => $item['data'],
      'tags'        => $item['tags'],
      'created_at'  => $item['created_at'],
      'updated_at'  => $item['updated_at'],
      'deleted_at'  => $item['deleted_at']
    ];
  }

}
