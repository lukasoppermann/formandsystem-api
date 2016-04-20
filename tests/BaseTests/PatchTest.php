<?php

namespace Lukasoppermann\Testing\BaseTests;

use Lukasoppermann\Testing\Traits\PatchTestTrait;

trait PatchTest
{
    use PatchTestTrait;
    /**
     * @test
     */
    public function patch_resource(){
        // PATCH RESOURCE
        $this->patchResourceById();
        // PATCH RESOURCE WRONG ID
        $this->patchResourceWrongId();
        // PATCH RESOURCE WRONG TYPE
        $this->patchResourceWrongType();
        // PATCH RESOURCE BY ID WITHOUT ATTRIBUTES
        $this->patchResourceByIdWithoutAttributes();
        // PATCH RESOURCE NO BODY
        $this->patchResourceByIdNoBody();
        // PATCH RESOURCE BODY MISSING ID
        $this->patchResourceIncompleteData();
        // PATCH RESOURCE WITH MULTIPLE RELATIONSHIPS
        $this->patchResourceWithMultipleRelationships();
        // POST RESOURECE WITH SINGLE RELATIONSHIP
        $this->patchResourceWithOneRelationship();
        // POST RESOURECE WITH WRONG RELATIONSHIPS
        $this->patchResourceWithWrongRelationships();
        // POST RESOURECE WITH WRONG RELATIONSHIP TYPE
        $this->patchResourceWithWrongRelationshipTypes();
    }
    /**
     * @test
     */
    public function patch_relationships(){
        // PATCH RELATIONSHIPS
        $this->patchRelationships();
        // PATCH RELATIONSHIPS USING WRONG RESOURCE ID
        $this->patchRelationshipsWithWrongResourceId();
        // PATCH RELATIONSHIPS WITH NO ITEMS
        $this->patchRelationshipsWrongRelationshipData();
        // PATCH RELATIONSHIPS TO WRONG TYPE URL
        $this->patchRelationshipsToWrongUrl();
    }
}
