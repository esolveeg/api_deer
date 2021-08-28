<?php

namespace App\QueryFilters;

use Closure;

class FromFilter
{
    //
    public function handle($request , Closure $next)
    {

        $builder = $next($request);
        if(!request()->has('from')){
            return $builder;
        }
        
        return $builder->whereDate('cart.created_at' , '>=' , request('from'));
    }

}