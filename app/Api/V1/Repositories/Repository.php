<?php

namespace App\Api\V1\Repositories;
/**
 * Repository Interface returns an eloquent collection
 *
 * @return Eloquent Collection
 */
interface Repository{

    public function all(Array $parameters);

    public function getById($id);
}
