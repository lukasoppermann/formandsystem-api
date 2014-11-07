<?php namespace Formandsystemapi\Repositories\Content;

use Formandsystemapi\Models\Content;
use Formandsystemapi\Repositories\EloquentAbstractRepository;
use \Carbon\Carbon;

class EloquentContentRepository extends EloquentAbstractRepository implements ContentRepositoryInterface
{

  protected $model;

  /**
  * Constructor
  */
  public function __construct(Content $model)
  {
    $this->model = $model;
  }

  public function getArticleId($idOrLink, $language = 'en')
  {
    if( is_numeric($idOrLink) && $record = $this->model->find($idOrLink) )
    {
      return $record->article_id;
    }
    elseif( $record = $this->model->where(['link' => $idOrLink, 'language' => $language])->first() )
    {
      return $record->article_id;
    }
  }

  /**
  * store a new page and return page id
  */
  public function storeModel($input)
  {
     // insert with next article id
     return $this->model->create(
        array_merge(
          $input,
          ['created_at' => Carbon::now()])
      );
  }
}
