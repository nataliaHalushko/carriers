<?php

namespace App\Http\Resources;

use App\Helpers\CollectionHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginateCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return LengthAwarePaginator
     */
    public function toArray($request)
    {
        return CollectionHelper::paginate($this->collection);
    }
}
