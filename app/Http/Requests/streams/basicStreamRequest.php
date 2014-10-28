<?php namespace Formandsystemapi\Http\Requests\streams;

use Formandsystemapi\Http\Requests\BasicRequest;

class basicStreamRequest extends BasicRequest {

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'position' => 'integer',
      'parent_id' => 'integer',
      'stream' => 'alpha_dash',
    ];
  }

}
