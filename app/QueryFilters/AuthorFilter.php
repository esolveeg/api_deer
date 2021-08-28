<?php

namespace App\QueryFilters;

use App\Group;
use App\Product;
use Closure;

class AuthorFilter
{
    //
    public function handle($request , Closure $next)
    {

        $builder = $next($request);
        if(! request()->has('author')){
            return $builder;
        }
        $id = (int)request('author');
        return $builder->where('authorId' , $id);
    }

}