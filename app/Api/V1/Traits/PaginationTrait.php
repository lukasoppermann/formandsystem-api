<?php

namespace App\Api\V1\Traits;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Api\V1\Requests\AbstractRequest;

trait PaginationTrait
{
    public function paginate(Collection $collection, AbstractRequest $request, $perPage = 20){
        // make parameters a collection
        $parameters = collect($request->all());
        $currentPage = $parameters->pull('page', 1);
        // return paginator
        return new LengthAwarePaginator(
            $collection->slice($perPage*($currentPage-1), $perPage),
            $collection->count(),
            $perPage,
            $currentPage,
            [
                'query' => $parameters->toArray(),
                'path'  => '/'.$request->path(),
            ]
        );
    }
}
