<?php namespace Formandsystemapi\Http\Requests\Settings;

use Formandsystemapi\Http\Requests\BasicRequest;

class basicSettingsRequest extends BasicRequest {

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'key' => 'alphanum',
      'settings' => '',
    ];
  }

}
