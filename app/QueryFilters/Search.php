<?php

namespace App\QueryFilters;

use Closure;

class Search
{
    //
    public function handle($request , Closure $next)
    {

        $builder = $next($request);
        if(! request()->has('search')){
            return $builder;
        }
        // TODO: add author name to search
        return $builder->where('productName' , 'LIKE' , '%'.request("search").'%');
    }

}