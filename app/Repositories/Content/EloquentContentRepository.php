<?php namespace Formandsystemapi\Repositories\Content;

use Formandsystemapi\Models\Content;
use Formandsystemapi\Repositories\EloquentAbstractRepository;
use \Carbon\Carbon;

class EloquentContentRepository extends EloquentAbstractRepository implements ContentRepositoryInterface
{

  protected $model;
  protected $limit;
  protected $offset;

  /**
  * Constructor
  */
  public function __construct(Content $model)
  {
    $this->model = $model;
  }

  /**
   * get a page id by link & language
   *
   * @return array
   */
  public function getArrayByLinkOrId($link, $language, $withTrashed = false)
  {
    if( !is_numeric($link) )
    {
      return $this->getArrayWhere(['link' => $link, 'language' => $language ], $withTrashed);
    }

    return $this->getArrayWhere(['id' => $link], $withTrashed);
  }

  /**
   * get a page by id and include stream info
   *
   * @return array
   */
  public function getArrayById($id, $withTrashed = false)
  {
    if( $page = $this->getById($id, $withTrashed) )
    {
      if( $stream = $page->stream()->withTrashed()->first() )
      {
        $stream = $page->stream()->withTrashed()->first();
        $stream['stream_record_id'] = $stream->id;

        return array_merge($stream->toArray(), $page->toArray());
      }
    }

    return false;
  }

  /**
   * get a pages and include stream info
   *
   * @return array
   */
  public function getArrayWhere($whereArray = [], $withTrashed = false)
  {
    $pages = $this->queryWhere($whereArray, $withTrashed)->with('stream')->get()->toArray();

    if( !is_array($pages) OR count($pages) == 0 )
    {
      return false;
    }

    foreach( $pages as $page )
    {
      $content = null;
      foreach($this->queryWhere(['article_id' => $page['article_id']], $withTrashed)->get()->toArray() as $cont)
      {
        $content[$cont['language']] = $cont;
      }

      $result[] = array_merge(array_merge([
        'article_id' => $page['article_id'],
        'id' => null,
        'stream' => null,
        'position' => null,
      ], (array) $page['stream']), ['content' => $content]);
    }

    return $result;
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

  /**
   * Update the specified page
   *
   * @param  int  $id
   * @return array | bool
   */
   public function updateModel($id, $input = [])
   {
      if( $page = $this->getById($id, true) )
      {
        // restore if deleted
        $page->restore();

        // update all changed values
        $page->update($input);

        return true;
      }

      return false;
   }
}
