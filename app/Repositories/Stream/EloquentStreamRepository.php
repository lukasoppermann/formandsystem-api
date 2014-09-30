<?php namespace Formandsystemapi\Repositories\Stream;

use Formandsystemapi\Models\Stream;
use Formandsystemapi\Repositories\EloquentAbstractRepository;

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
  * get the specified resource in storage.
  *
  * @param  int  $article_id
  * @return Response
  */
  public function getByArticleId($article_id, $withTrashed = false)
  {
    $query = $this->model->where('article_id', $article_id);

    if( $withTrashed === true )
    {
      return $query->withTrashed();
    }

    return $query;
  }

  public function storeRecord($input){}

  /**
  * update the specified resource in storage
  *
  * @param  int  $article_id
  * @return record | bool
  */
  public function updateRecord($id, $input = [])
  {
    if( $record = $this->getById($id, true) )
    {
      // restore if deleted
      $record->restore();

      // update all changed values
      foreach( array_filter($input) as $key => $value )
      {
        $record->$key = $value;
      }

      //save model
      $record->save();

      return $record;
    }

    return false;
  }


  /**
  * delete the specified resource in storage
  *
  * @param  int  $article_id
  * @return bool
  */
  public function deleteRecord($id)
  {
    return $this->getById($id)->delete();
  }

}
