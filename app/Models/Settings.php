<?php namespace Formandsystemapi\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model{

  /**
  * The database table used by the model.
  *
  * @var string
  */
  protected $connection = 'user';
  protected $table = 'fs_settings';
  protected $fillable = array('key','setting');
  public $timestamps = true;

}
