<?php

namespace Lukasoppermann\Testing\BaseTests;

use Lukasoppermann\Testing\Traits\GetTestTrait;

trait GetTest
{
    use GetTestTrait;
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
