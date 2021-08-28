<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $guarded = [];
    public function scopeActive($query)
    {
        return $query->where('active' , true);
    }


    public function scopeMain($query)
    {
        return $query->where('groupId' , null);
    }

    public function groups()
    {
        return $this->hasMany(Group::class , 'groupId' , 'id');
    }
    public function products()
    {
        return $this->hasMany(Product::class , 'groupId' , 'id');
    }
}