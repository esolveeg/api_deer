<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $guarded = [];
    public function getImageAttribute($value)
    {
        // dd($value);
        return $value && file_exists('images/'.$value) ? asset('images/' . $value) : $value;
    }
}
