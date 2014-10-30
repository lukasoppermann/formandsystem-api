<?php namespace Formandsystemapi\Services;

/**
 * NestArrayService
 */
class NestArrayService
{

  /**
   * nest array by parent-child-relationship
   *
   * @method nest
   *
   * @param  array $data
   *
   * @return array
   */
  public function nest($data)
  {
    foreach($data as $item)
    {
      $array[$item['parent_id'] == NULL ? 0 : $item['parent_id']][$item['stream_record_id']] = $item;
    }

    return $this->nestLoop(0,$array);
  }

  /**
   * loop through array and return nested
   *
   * @method nestLoop
   *
   * @param  int $index
   * @param  array &$array
   *
   * @return array
   */
  private function nestLoop( $index, &$array )
  {
    if( isset($array[$index]) )
    {

      foreach($array[$index] as $item)
      {
        // calc Position (in case double-assigned)
        $pos = $this->calcPosition($nested, $item['position']);

        // add item
        $nested[$pos] = array_merge($item, ['position' => $pos]);

        // check for children
        $nested[$pos]['children'] = $this->nestLoop($item['stream_record_id'], $array);
      }

      return $nested;

    }

    // return array to stay consistent
    return [];
  }

  /**
   * calculate the position in case the set position is already taken
   *
   * @method calcPosition
   *
   * @param  array       $nested
   * @param  int       $pos
   *
   * @return int
   */
  private function calcPosition(&$nested, $pos)
  {

    while( isset($nested[$pos]) )
    {
      $pos++;
    }

    return $pos;
  }

}
