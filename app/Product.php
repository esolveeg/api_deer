<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    public function scopeActive($query)
    {
        return $query->where('acitve' , true);
    }
    public function scopeAvilable($query)
    {
        return $query->where('inStock' , true);
    }

    public function getItemImageAttribute($value)
    {
        return file_exists('images/'.$value) ? asset('images/' . $value) : $value;
    }
    
}
