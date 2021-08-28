<?php

namespace App\QueryFilters;

use Closure;

class Sort
{
    //
    public function handle($request , Closure $next)
    {
        $builder = $next($request);
        if(! request()->has('sort')){
            return $builder;
        }
        // TODO: add author name to sort
        $type = request('sortType') ? request('sortType') : 'ASC';
        return $builder->orderBy(request('sort') , $type);
       
    }

}