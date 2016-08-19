<?php

use Lukasoppermann\Httpstatus\Httpstatuscodes;
use Lukasoppermann\Testing\Traits\TestTrait;
use Lukasoppermann\Testing\Traits\ValidationTrait;
use Lukasoppermann\Testing\Traits\DeleteTestTrait;
use Lukasoppermann\Testing\Traits\GetTestTrait;
use Lukasoppermann\Testing\Traits\PostTestTrait;
use Lukasoppermann\Testing\Traits\PatchTestTrait;
use Illuminate\Support\Facades\Artisan as Artisan;

class ResourceTestCase extends TestCase
{
    use ValidationTrait;
    use GetTestTrait;
    use PostTestTrait;
    use PatchTestTrait;
    use DeleteTestTrait;

    // resource objects
    protected $resourceObjects = [
        'Metadetail',
        'Page',
        'Collection',
        'Fragment',
        'Image'
    ];
    /**
     * @method setUp
     */
    public function setUp()
    {
        parent::setUp();
        // Create resources
        $this->initResources();
    }
    /**
     * create resource objects
     *
     * @method initResources
     *
     * @return void
     */
    protected function initResources(){
        // init resources
        foreach($this->resourceObjects as $resource){
            $Resourceclass = 'Lukasoppermann\Testing\Resources\\'.$resource.'Resource';
            $this->resources[strtolower($resource).'s'] = new $Resourceclass;
        }
    }

}
