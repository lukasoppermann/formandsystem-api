<?php namespace Formandsystemapi\Repositories\Stream;

use Formandsystemapi\Models\Stream;
use Formandsystemapi\Repositories\EloquentAbstractRepository;
use Illuminate\Support\Collection;

class EloquentStreamRepository extends EloquentAbstractRepository implements StreamRepositoryInterface
{

  protected $model;

  /**
  * Constructor
  */
  public function __construct(Stream $model)
  {
    $this->model = $model;
  }

  /**
   * get all different stream names
   *
   * @method getStreamsArray
   */
  public function getStreamsArray()
  {
    return $this->model->get()->groupBy('stream')->toArray();
  }

  /**
   * get a streams and include page info
   *
   * @return array
   */
  public function getWhere($whereArray = [], $parameters = [])
  {
    // merge parameters
    $parameters = array_merge([
      'offset' => 0,
      'limit' => 20,
      'from' => null,
      'until' => null,
      'withtrashed' => 'false',
      'language' => 'en',
      'withdrafts' => 'false',
    ], $parameters);

    // get model
    $records = $this->modelWithTrashed($parameters['withtrashed'])->orderBy('position');

    // parse where conditions
    $records->where($whereArray);

    // add content, get and turn into array
    $records->with(['content' => function($query) use ($parameters)
    {
      // include trashed if wanted
      if( $parameters['withtrashed'] === 'true' )
      {
        $query->withTrashed();
      }

      // apply published
      $drafts[] = '';
      if( $parameters['withdrafts'] != 'true' )
      {
        $drafts[]  = 'published = 1 and ';
      }

      // apply from
      if( $parameters['from'] != null )
      {
        $where[]  = 'created_at > ? and ';
        $values[] = $parameters['from'].' 00:00:01';
      }

      // apply until
      if( $parameters['until'] != null )
      {
        $where[]  = 'created_at < ? and ';
        $values[] = $parameters['until'].' 23:59:59';
      }

      // add language for 3rd and 4th parameter
      $values[] = $parameters['language'];
      $values[] = $parameters['language'];

      // needed here in case from & until are both NOT defined so implode has something to work with
      $where[] = 'language = ?';

      $query->whereRaw(implode('',$drafts).'( ('.implode('',$where).') OR language != ? )', $values )->with('tags');

    }]);

    // return and apply limit & offset
    return array_slice(
      $this->sortByLanguage( $records->get()->toArray(), $parameters['language'] ),
      $parameters['offset'], $parameters['limit']
    );
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
        $records[$key]['content'][$cont['language']] = $cont;
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
