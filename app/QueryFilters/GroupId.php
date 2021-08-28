<?php

namespace App\QueryFilters;

use App\Group;
use App\Product;
use Closure;

class groupId
{
    //
    public function handle($request , Closure $next)
    {

        $builder = $next($request);
        if(! request()->has('group')){
            return $builder;
        }
        $id = (int)request('group');
        $child = Group::select('id')->where('groupId' ,  $id)->first();
        if($child != null) $id = $child->id;
        
        return $builder->where('groupId' , $id);
    }

}