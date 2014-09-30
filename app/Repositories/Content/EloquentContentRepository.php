<?php namespace Formandsystemapi\Repositories\Content;

use Formandsystemapi\Models\Content;
use Formandsystemapi\Repositories\EloquentAbstractRepository;

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
  public function getPageByLink($link, $language, $withTrashed = false)
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

    return $this->getPageById($link, $withTrashed);
  }
  /**
   * get a page by id and include stream info
   *
   * @return array
   */
  public function getPageById($id, $withTrashed = false)
  {
    if( $page = $this->getById($id, $withTrashed) )
    {
      $stream = $page->stream()->first();

      return array_merge($stream->toArray(), $page->toArray());
    }
    
    return false;
  }

  /**
  * store a new page and return page id
  */
  public function storePage($parameters)
  {
     // insert with next article id
     $page = Content::create([
       'article_id' => $parameters['article_id'],
       'menu_label' => $parameters['menu_label'],
       'link' => $parameters['link'],
       'status' => $parameters['status'],
       'language' => $parameters['language'],
       'data' => $parameters['data'],
       'tags' => $parameters['tags'],
       'created_at' => date("Y-m-d h:i:s"),
     ]);

     return (is_numeric($page->id) ? $page : false);
  }

  public function deletePage($id, $parameters)
  {

  }
}
