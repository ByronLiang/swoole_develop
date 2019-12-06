<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractPaginator;

class GanguoCollection extends ResourceCollection
{
    public function toResponse($request)
    {
        if (!$this->resource instanceof AbstractPaginator) {
            return $this->resource->toArray();
        }

        return [
            'content' => $this->resource->getCollection(),
            'pagination' => array_except($this->resource->toArray(), [
                'data',
                'first_page_url',
                'last_page_url',
                'prev_page_url',
                'next_page_url',
            ]),
        ];
    }
}
