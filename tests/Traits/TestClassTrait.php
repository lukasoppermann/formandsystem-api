<?php

namespace Lukasoppermann\Testing\Traits;


trait TestClassTrait
{
    /**
     * validateTestCount
     * @test
     */
    public function validateTestCount()
    {
        if($this->testcount !== $this->tests){
            throw new \Exception('Tests missing only '.$this->testcount.' of '.$this->tests.' tests have been executed.');
        }
    }
}
