<?php

namespace App\QueryFilters;

use Closure;

class PriceTo
{
    //
    public function handle($request , Closure $next)
    {
        $builder = $next($request);
        if(! request()->has('priceTo')){
            return $builder;
        }
        
        return $builder->where('price' , '<=' , request('priceTo'));
    }

}