<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public function getValueAttribute($value)
    {
        // dd($value);
        // dd($value);
        if($this->type == 'image'){
            return $value && file_exists('images/'.$value) ? asset('images/' . $value) : $value;
        }
        return $value;
    }
}
