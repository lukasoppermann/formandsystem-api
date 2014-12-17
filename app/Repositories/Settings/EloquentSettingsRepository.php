<?php namespace Formandsystemapi\Repositories\Settings;

use Formandsystemapi\Models\Settings;
use Formandsystemapi\Repositories\EloquentAbstractRepository;
use Illuminate\Support\Collection;

class EloquentSettingsRepository extends EloquentAbstractRepository implements SettingsRepositoryInterface
{

  protected $model;

  /**
  * Constructor
  */
  public function __construct(Settings $model)
  {
    $this->model = $model;
  }

  /**
   * get all different stream names
   *
   * @method getSettingsArray
   */
  public function getAll()
  {
    return $this->model->get()->groupBy('group')->toArray();
  }

  /**
   * get a Settings and include page info
   *
   * @return array
   */
  public function getByGroup( $group )
  {
    return $this->model->where('group', '=', $group)->get()->toArray();
  }

  /**
   * sort the content array by language
   *
   * @method sortByLanguage
   *
   * @param  array $records
   *
   * @return array
   */
  private function sortByLanguage($records, $lang)
  {
    // sort content by language
    foreach($records as $key => $value)
    {

      unset($records[$key]['content']);

      // sort by language
      foreach($value['content'] as $cont)
      {
        $records[$key]['content'][$cont['language']] = $this->inlineFragments($cont);
      }

      // remove entries without any content
      if( !isset($records[$key]['content'])
          || count($records[$key]['content']) < 1
          || ( $lang != null && !isset($records[$key]['content'][$lang]) )
      )
      {
        unset($records[$key]);
      }
    }

    return $records;
  }

  /**
   * places fragments inside content structure
   *
   * @method inlineFragments
   *
   * @param  array          $records
   */
  protected function inlineFragments( $records )
  {
    foreach($records['fragments'] as $fragment)
    {
      $fragments[$fragment['id']] = [
        'key' => $fragment['key'],
        'data' => $fragment['data']
      ];
    }
    unset($records['fragments']);

    foreach( json_decode($records['data'], true) as $key => $data )
    {
      $rec[$key]['content'] = $fragments[$data['fragment']]['data'];
      $rec[$key]['fragment_key'] = $fragments[$data['fragment']]['key'];
    }
    $records['data'] = $rec;

    return $records;
  }

  /**
   * Update the specified record
   *
   * @param  int  $id
   * @return array | bool
   */
  public function storeModel($input)
  {
    // insert with next article id
    return $this->model->create(
        array_merge(
          $input,
          ['article_id' => $this->model->orderBy('article_id','desc')->first()->article_id+1]
        )
    );
  }

}
