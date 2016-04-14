<?php

namespace Lukasoppermann\Testing\Resources;

use Illuminate\Database\Eloquent\Collection;

abstract class ApiResource
{
    /**
     * return properties as collections
     *
     * @method __get
     *
     * @return Collection
     */
    public function __get($property){
        if (property_exists($this, $property)) {
            return new Collection($this->$property);
        }
        if (method_exists($this, $property)) {
            return new Collection($this->$property());
        }
    }
    /**
     * returns available filters
     *
     * @method filter
     *
     * @return array
     */
    abstract public function filter();
    /**
     * returns expected blueprint
     *
     * @method blueprint
     *
     * @return array
     */
    abstract public function blueprint();
    /**
     * returns full data set, ready for post
     *
     * @method data
     *
     * @return array
     */
    abstract public function data();
    /**
     * returns incomplete data set
     *
     * @method incomplete
     *
     * @return array
     */
    abstract public function incomplete();

}
