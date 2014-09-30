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

  public function storeRecord($parameters){}

  public function updateRecord($id, $parameters){}

  public function deleteRecord($id){}

}
