<?php

namespace Lukasoppermann\Testing\BaseTests;

use Lukasoppermann\Testing\Traits\GetTestTrait;

trait GetTest
{
    use GetTestTrait;
    /**
     * @test
     */
    public function get_resource()
    {
        // get index
        $this->getResource();
        // BY ID
        $this->getResourceById();
        // BY WRONG ID
        $this->getResourceByWrongId();
        // BY FILTER
        $this->getResourceByFilter('slug');
        // BY WRONG FILTER
        $this->getResourceByWrongFilter();
        // BY WRONG FILTER PARAMETER
        $this->getResourceByWrongFilterParameter();
    }
    /**
     * @test
     */
    public function get_related()
    {
        // GET RELATED
        $this->getRelated();
        // GET RELATED WITH NO ITEMS
        $this->getRelatedNoRelatedItems();
        // GET RELATED USING WRONG RESOURCE ID
        $this->getRelatedWithWrongResourceId();
    }
    /**
     * @test
     */
    public function get_relationships()
    {
        // GET RELATIONSHIPS
        $this->getRelationships();
        // GET RELATIONSHIPS WITH NO ITEMS
        $this->getRelationshipsNoRelatedItems();
        // GET RELATIONSHIPS USING WRONG RESOURCE ID
        $this->getRelationshipsWithWrongResourceId();
    }
}
