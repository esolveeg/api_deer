<?php

namespace App\QueryFilters;

use Closure;

class PriceFrom
{
    //
    public function handle($request , Closure $next)
    {
        $builder = $next($request);
        if(! request()->has('priceFrom')){
            return $builder;
        }
        
        return $builder->where('price' , '>=' , request('priceFrom'));
    }

}