<?php

namespace Lukasoppermann\Testing\BaseTests;

use Lukasoppermann\Testing\Traits\PostTestTrait;

trait PostTest
{
    use PostTestTrait;
    /**
     * @test
     */
    public function post_resource(){
        // POST RESOURECE
        $this->postResource();
        // POST RESOURECE WITH RELATIONSHIPS
        $this->postResourceWithMultipleRelationships();
        // POST RESOURECE WITH SINGLE RELATIONSHIP
        $this->postResourceWithOneRelationship();
        // POST RESOURECE WITH WRONG RELATIONSHIPS
        $this->postResourceWithWrongRelationships();
        // POST RESOURECE WITH WRONG RELATIONSHIP TYPE
        $this->postResourceWithWrongRelationshipTypes();
        // POST RESOURECE WITH WRONG TYPE
        $this->postResourceWrongType();
        // POST RESOURECE WITH INCOMPLETE DATA
        $this->postResourceIncompleteData();
        // POST RESOURECE WITH ADDITONAL NOT SUPPORTED DATA
        $this->postResourceAdditonalData();
        // POST RESOURECE WITH NO BODY
        $this->postResourceNoBody();
        // POST RESOURECE WITH INCOMPLETE RELATIONSHIP
        $this->postResourceIncompleteRelationship();
    }
    /**
     * @test
     */
    public function post_relationships()
    {
        // POST RELATIONSHIPS
        $this->postRelationships();
        // POST RELATIONSHIPS USING WRONG RESOURCE ID
        $this->postRelationshipsWithWrongResourceId();
        // POST RELATIONSHIPS WITH NO ITEMS
        $this->postRelationshipsWrongRelationshipData();
        // POST RELATIONSHIPS TO WRONG TYPE URL
        $this->postRelationshipsToWrongUrl();
    }
}
