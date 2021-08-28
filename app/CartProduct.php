<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartProduct extends Model
{
    protected $table = "cart_product";
    protected $guarded = [];
    use SoftDeletes;


}
