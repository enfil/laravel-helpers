<?php

namespace Enfil\Laravel\Helpers\Pagination;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LengthAwarePaginatorConverter
{
    public static function convert(LengthAwarePaginator $paginator): \Illuminate\Pagination\LengthAwarePaginator
    {
        return new \Illuminate\Pagination\LengthAwarePaginator(
            collect($paginator->items()),
            $paginator->total(),
            $paginator->perPage(),
            $paginator->currentPage(),
        );
    }
}
