<?php namespace Abstraction\Repositories;

use Stream;

class EloquentStreamRepository extends AbstractEloquentRepository implements StreamRepositoryInterface {

  /**
  * Constructor
  */
  public function __construct(Stream $model)
  {
    $this->model = $model;
  }

  /**
  * GET first page from stream
  *
  * @return array
  */
  public function getFirst($stream)
  {
    // get item
    $item = $this->model->whereRaw('stream = ? and parent_id = 0 and position = 1', array($stream))->with('content')->first()->toArray();
    // merge content to top level
    $item = array_merge($item, $item['content'][key($item['content'])]);
    unset($item['content']);
    // decode json in data field
    $item['data'] = $this->jsonDecode($item['data']);
    // return
    return $item;
  }

  /**
   * GET pages from stream
   *
   * @return array
   */
  public function getStream( $stream, $parameters = array() )
  {
    if( isset($parameters['first']) && $parameters['first'] == "true" )
    {
      return $this->getFirst($stream);
    }
    // set limit
    $limit = isset($parameters['limit']) && is_int((int) $parameters['limit']) ? $parameters['limit'] : 20;
    // set offset
    $offset = isset($parameters['offset']) && is_int((int) $parameters['offset']) ? $parameters['offset'] : 0;

    $stream = $this->model->where('stream', $stream)->orderBy('parent_id','asc')->orderBy('position','asc')->with('content')
      ->whereHas('content',function($query) use ($parameters)
      {
        // Apply parameters to "content"

        // basic parameters
        $basicParameters = array('language', 'status');
        // apply parameters
        foreach($basicParameters as $key)
        {
          if( array_key_exists($key, $parameters) )
          {
            $query->where($key,$parameters[$key]);
          }
        }

        // if until is set
        if( isset($parameters['until']) && $this->_validateDate($parameters['until']) )
        {
          $query->where('created_at','<=',$parameters['until']);
        }

        // if since is set
        if( isset($parameters['since']) && $this->_validateDate($parameters['since']) )
        {
          $query->where('created_at','>=',$parameters['since']);
        }

      }
      // apply limit, offset & fields
    )->select($parameters['fields'])->skip($offset)->take($limit)->get();


    // Unpack content
    $stream = $stream->map(function($item){
      $c = $item->content[0];
      unset($item->content);

      return array_merge((array) $item->toArray(), (array) $c->toArray());
    });

    // sorting (sort=asc:[date,name],desc:[user] || sort=desc:date)
    // if both asc and desc, needs group []
    if( isset($parameters['sort']) && $parameters['sort'] != "false" )
    {
      $sortParams = [$parameters['sort']];
      if( $pos = strpos($sortParams[0],'],') )
      {
        $sortParams = [
          substr($sortParams[0], 0, $pos+1),
          substr($sortParams[0], $pos+2)
        ];
      }

      foreach($sortParams as $s)
      {
        $s = explode(':',$s);

        if( count($s) < 2 )
        {
          $s[1] = $s[0];
          $s[0] = 'asc';
        }

        $sorting[$s[0]] = explode(',',trim($s[1],'[]'));
      }

      foreach($sorting as $direction => $fields)
      {
        if( $direction == 'desc' )
        {
          foreach($fields as $field)
          {
            $stream = $stream->sortByDesc($field);
          }
        }
        else
        {
          foreach($fields as $field)
          {
            $stream = $stream->sortBy($field);
          }
        }
      }
    }

    // nest items in tree structure
    if( isset($parameters['nested']) && $parameters['nested'] == 'true' )
    {
      $stream = $this->nested($stream);
    }

    return $stream;
  }


  /**
   * nested structure
   *
   * @return array
   */
  public function nested( $stream )
  {
    foreach($stream as $key => $item)
    {
      $navArray[$item['parent_id'] == NULL ? 0 : $item['parent_id']][$item['id']] = $item;
    }
    // build tree
    $nav = $this->_loop(0,$navArray);
    // return
    return $nav;
  }


  /**
   * loop
   *
   * @return mixed
   */
  private function _loop($index, &$arr)
  {
    if( isset($arr[$index]) )
    {
      foreach($arr[$index] as $key => $item)
      {
        // check to not have doubled position values
        $i = 0;
        while( isset($nav[$item['position']+$i]) )
        {
          $i++;
        }
        // add item
        $nav[$item['position']+$i] = $item;
        $nav[$item['position']+$i]['position'] = $item['position']+$i;
        // check for children
        if( isset($arr[$item['id']]) )
        {
          $nav[$item['position']+$i]['children'] = $this->_loop($item['id'], $arr);
        }
      }
      return $nav;
    }
    return $index;
  }


/**
 * store stream item with stream
 *
 * @return int
 */
  public function storeStreamItem( $parameters = array() )
  {
    // get position
    $pos = &$parameters['position'];
    while($this->model->where('stream',$parameters['stream'])->where('position',$pos)->get()->count() > 0){
      $pos++;
    }

    // insert with next article id
    $stream = Stream::create([
      'article_id' => $this->model->orderBy('article_id','desc')->first()->article_id+1,
      'parent_id' => $parameters['parent_id'],
      'stream' => $parameters['stream'],
      'position' => $pos
    ]);

    return (is_int($stream->id) ? $stream : false);
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


  /**
  * delete the specified resource in storage.
  *
  * @param  int  $article_id
  * @return Response
  */
  public function deleteByArticleId($article_id)
  {
    return $this->getByArticleId($article_id)->delete();

  }


  /**
  * restore the specified resource in storage.
  *
  * @param  int  $article_id
  * @return Response
  */
  public function restoreByArticleId($article_id)
  {
    return $this->getByArticleId($article_id, true)->restore();
  }

}
