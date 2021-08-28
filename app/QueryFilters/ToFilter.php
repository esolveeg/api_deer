<?php

namespace App\QueryFilters;

use Closure;

class ToFilter
{
    //
    public function handle($request , Closure $next)
    {

        $builder = $next($request);
        if(!request()->has('to')){
            return $builder;
        }
        
        return $builder->whereDate('cart.created_at' , '<=' , request('to'));
    }

}