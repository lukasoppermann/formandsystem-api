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

  /**
   * get a page id by link & language
   *
   * @return array
   */
  public function getArrayByLink($link, $language, $withTrashed = false)
  {
    if( !is_numeric($link) )
    {
      $link = $this->model->whereRaw('link = ? and language = ?', array($link, $language) )->first();

      if( is_null($link) )
      {
        return false;
      }

      $link = $link->id;
    }

    return $this->getArrayById($link, $withTrashed);
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
   * get a page by id and include stream info
   *
   * @return array
   */
  public function getArrayWhere($whereArray = [], $withTrashed = false)
  {
    $pages = $this->queryWhere($whereArray, $withTrashed)->with('stream')->get()->toArray();

    foreach($pages as $key => $value)
    {
      if( isset($value['stream']) )
      {
        $pages[$key]['stream_record_id'] = $value['stream']['id'];
        $pages[$key]['stream'] = $value['stream']['stream'];
        $pages[$key]['position'] = $value['stream']['position'];
      }
    }

    return $pages;
  }

  /**
  * store a new page and return page id
  */
  public function storeModel($input)
  {
     // insert with next article id
     return Content::create( array_merge($input, ['created_at' => Carbon::now()]) );
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

  /**
   * delete the specified page
   *
   * @param  int  $id
   * @return bool
   */
  public function deleteModel($id)
  {
    if( $page = $this->getById($id) )
    {
      $page->delete();

      return true;
    }

    return false;
  }
}
