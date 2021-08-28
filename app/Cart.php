<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Cart extends Model
{
    protected $table = "cart";
    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
    ];
    protected $guarded = [];

    public function scopeCart($query)
    {
        return $query->where('closedAt', null);
    }
    // public function getCreatedAtAttribute($value)
    // {
    //     return ucfirst($value);
    // }
    public function scopeOrders($query)
    {
        return $query->where('closedAt', '!=', null);
    }


    public function loadProucts(){
        //select cart products epending on pivot table (cartProduct)
        $products = DB::select(
            "SELECT 
                        p.* ,
                        cp.price ,
                        cp.cartId ,
                        cp.image,
                        cp.qnt 
                        FROM cart_product cp 
                        JOIN products p 
                            ON cp.productId = p.id
                        WHERE cp.cartId = ?
                        AND cp.deleted_at IS NULL",
            [$this->id]
        );
        //validate that cart has products
        if (count($products) == 0) {
            $this->products = [];
            return response()->json(['products' => []]);
        }

        //set products to the cart variable
        $this->products = $products;
        // get the cart subtotal by sum the price multiplied by qnt for all products on thin cart id
        // not that we load all our data from pivot table to avoid updating prices issues
        // so we depend on the price on the pivot table not on products table
        $subtotal = DB::select("SELECT SUM(price * qnt) subtotal FROM cart_product WHERE cartId = ? AND deleted_at IS NULL", [$this->id])[0]->subtotal;
        $this->subtotal = $subtotal;

        //check if there is discount on thins cart
        // select coupon with the same code
        // set dicount value depending on the coupn type [valud , percent]
        $discountVal = 0;
        if ($this->discountCode != null) {
            $coupon = Coupon::where('code', $this->discountCode)->first();
            if ($coupon->type == 'fixed') {
                $discountVal = $coupon->value;
            } else {
                $this->percentOff = $coupon->value;
                $discountVal =  $coupon->value * $subtotal / 100;
            }
            $this->discounVal = $discountVal;
        }
        foreach ($this->products as $pr) {
            $pr->image = $pr->image && file_exists('images/' . $pr->image) ? asset('images/' . $pr->image) : $pr->image;
        }
        // set cart total as substracting discount and adding shippint to the subtotal
        $this->total = $subtotal - $discountVal +  $this->shipping;

        return $this;
    }

    public function getClosedAtAttribute($value)
    {
        return Carbon::parse($value)->format('M-d-Y');
    }
}
