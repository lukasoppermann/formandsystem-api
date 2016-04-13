<?php

namespace Lukasoppermann\Testing\BaseTests;

use Lukasoppermann\Testing\Traits\DeleteTestTrait;

trait DeleteTest
{
    use DeleteTestTrait;
    /**
     * @test
     */
    public function delete_resource(){
        // DELETE BY ID
        $this->deleteResourceById();
        // DELETE BY ID
        $this->deleteResourceByWrongId();
    }
    /**
     * @test
     */
    public function delete_relationships()
    {
        // DELETE RELATIONSHIPS
        $this->deleteRelationships();
        // DELETE RELATIONSHIPS WITH NO ITEMS
        $this->deleteRelationshipsWrongRelationshipData();
        // DELETE RELATIONSHIPS USING WRONG RESOURCE ID
        $this->deleteRelationshipsWithWrongResourceId();
    }
}
