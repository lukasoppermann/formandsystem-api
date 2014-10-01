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
  public function getArrayWhere($whereArray, $withTrashed = false)
  {
    $query = $this->withTrashed($withTrashed);

    foreach ($whereArray as $key => $value)
    {
      $operator = "=";

      if( is_array($value) )
      {
        $operator = $value[0];
        $value = $value[1];
      }

      $query = $query->where($key, $operator, $value);
    }

    return $query->get()->toArray();
  }

  /**
  * store a new page and return page id
  */
  public function storeModel($input)
  {
     // insert with next article id
     $page = Content::create([
       'article_id' => $input['article_id'],
       'menu_label' => $input['menu_label'],
       'link' => $input['link'],
       'status' => $input['status'],
       'language' => $input['language'],
       'data' => $input['data'],
       'tags' => $input['tags'],
       'created_at' => Carbon::now(),
     ]);

     return (is_numeric($page->id) ? $page : false);
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
        foreach( array_filter($input) as $key => $value )
        {
          $page->$key = $value;
        }

        //save model
        $page->save();

        // return page (including stream data = array)
        return $this->getArrayById($id, true);
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
    }

    return $this->getArrayById($id, true);
  }
}
