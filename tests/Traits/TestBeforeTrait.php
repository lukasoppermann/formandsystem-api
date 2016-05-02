<?php

namespace Lukasoppermann\Testing\Traits;


trait TestBeforeTrait
{
    /**
     * @before
     */
    public function increaseTestCount()
    {
        $this->testcount++;
    }
}
