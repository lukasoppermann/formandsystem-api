<?php namespace Formandsystemapi\Transformers;

class SettingsTransformer extends Transformer{

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
    $setting = json_decode($item['setting'], true);
    if( !is_array($setting) )
    {
      $setting = $item['setting'];
    }

    return [
      'key'      => $item['key'],
      'setting'  => $setting,
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
    return $data;
    // // assign names
    // $names = [
    //   'status' => 'status',
    //   'language' => 'language',
    //   'article_id' => 'article_id',
    //   'data' => 'data',
    //   'tags' => 'tags',
    //   'menu_label' => 'menu_label',
    //   'link' => 'link',
    //   'parent_id' => 'parent_id',
    //   'position' => 'position'
    // ];
    //
    // // transform given data
    // foreach( array_filter( $data ) as $key => $value )
    // {
    //   $output[$names[$key]] = $value;
    // }
    //
    // // transform tags
    // if( isset($output['tags']) )
    // {
    //   $output['tags'] = implode(', ',$output['tags']);
    // }
    //
    // // transform data
    // if( isset($output['data']) )
    // {
    //   $output['data'] = json_encode($output['data']);
    // }
    //
    return $output;
  }


}
