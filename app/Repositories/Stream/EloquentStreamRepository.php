<?php namespace Formandsystemapi\Repositories\Stream;

use Formandsystemapi\Models\Stream;
use Formandsystemapi\Repositories\EloquentAbstractRepository;
use Illuminate\Support\Collection;

class EloquentStreamRepository extends EloquentAbstractRepository implements StreamRepositoryInterface
{

  protected $model;
  protected $limit;
  protected $offset;

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
  public function getArrayWhere($whereArray = [])
  {
    $streams = $this->queryWhere($whereArray, false)->orderBy('position');

    if( isset($this->limit) )
    {
        $streams->take($this->limit);
    }

    $streams = $streams->with('content')->get()->toArray();

    foreach($streams as $key => $value)
    {
      if( isset($value['content']) )
      {
        unset($streams[$key]['content']);
        foreach($value['content'] as $cont)
        {
          $streams[$key]['content'][$cont['language']] = $cont;
        }
      }
    }

    return $streams;
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

  /**
  * update the specified resource in storage
  *
  * @param  int  $article_id
  * @return record | bool
  */
  public function updateModel($stream_record_id, $input = [])
  {
    if( $record = $this->getById($stream_record_id, true) )
    {
      // restore if deleted
      $record->restore();

      // update all changed values
      $record->update($input);

      return true;
    }

    return false;
  }

}
