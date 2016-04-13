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
        // PATCH RESOURCE BY ID WITHOUT ATTRIBUTES
        $this->patchResourceByIdWithoutAttributes();
        // PATCH RESOURCE NO BODY
        $this->patchResourceByIdNoBody();
        // PATCH RESOURCE BODY MISSING ID
        $this->patchResourceIncompleteData();
    }
    /**
     * @test
     */
    public function patch_relationships(){
        $this->fail('Some tests deactivated');
    }
}
