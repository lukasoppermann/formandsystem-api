<?php namespace Formandsystemapi\Http\Requests\pages;

use Formandsystemapi\Http\Requests\BasicRequest;

class basicPageRequest extends BasicRequest {

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'format' => 'in:json',
      'language' => 'alpha',
      'limit' => 'integer',
      'offset' => 'integer',
      'fields' => '',
      'status' => 'integer',
      'until' => '',
      'since' => '',
      'withDeleted' => 'string',
      'sort' => '',
      'first' => 'string',
      'position' => 'integer',
      'parent_id' => 'integer',
      'link' => 'alpha_dash',
      'article_id' => 'integer',
      'menu_label' => '',
      'data' => '',
      'tags' => '',
      'stream' => 'alpha_dash',
    ];
  }

}
