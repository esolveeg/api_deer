<?php

namespace App\Http\Controllers;

use App\Address;
use App\Cart;
use App\CartProduct;
use App\Coupon;
use App\CouponUser;
use App\Http\Requests\CartRequest;
use App\Product;
use App\ProductImage;
use App\ProductOption;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{

    public function get(Request $request)
    {
        // carch user from the request
        // get the cart of this user
        // validate cart is exist or return empty record if its not
        $id = $request->user()->id;
        $cart = Cart::where('userId', $id)->cart()->first();
        if ($cart == null) {
            return response()->json(['products' => []]);
        }
        
        return response()->json($cart->loadProucts());
    }

    public function getTotals(Request $request)
    {
        $id = $request->user()->id;
        $cart = Cart::where('userId', $id)->select(['id', 'shipping', 'discountCode'])->cart()->first();
        $subtotal = DB::select("SELECT SUM(price * qnt) subtotal FROM cart_product WHERE cartId = ? AND deleted_at IS NULL", [$cart->id])[0]->subtotal;
        $discountVal = 0;
        if ($cart->discountCode != null) {
            $coupon = Coupon::where('code', $cart->discountCode)->first();
            if ($coupon->type == 'fixed') {
                $discountVal = $coupon->value;
            } else {
                $cart->percentOff = $coupon->value;
                $discountVal =  $coupon->value * $subtotal / 100;
            }
            $cart->discounVal = $discountVal;
        }
        $cart->subtotal = $subtotal;
        $cart->total = $subtotal - $discountVal +  $cart->shipping;
        return response()->json($cart);
    }

    public function checkout(Request $request)
    {
        $id = $request->user()->id;
        $cart = Cart::where('userId', $id)->cart()->first();
        if ($cart == null) {
            return response()->json('no items on your cart', 400);
        }
        if ($cart->addressId == null) {
            return response()->json('Please select address', 400);
        }
        $cart->closedAt = now();
        $cart->save();
    }
    public function applyCoupon(Request $request)
    {
        $id = $request->user()->id;

        $coupon = Coupon::where('code', $request->code)->first();
        if ($coupon == null) {
            return response()->json('this coupon dosen\'t exists', 400);
        }
        $used = CouponUser::where('couponId', $coupon->id)->where('userId', $id)->first() !== null;
        if ($used) {
            return response()->json('you used this coupon previousily', 400);
        }
        $expiresAt = Carbon::createFromFormat('Y-m-d', $coupon->expires_at);
        $expired = $expiresAt->lt(Carbon::now()->format('Y-m-d'));
        // dd($id);
        // dd($expired);
        if ($expired == true) {
            return response()->json('this coupon is expired', 400);
        }
        $cart = Cart::where('userId', $id)->cart()->first();
        if ($cart == null) {
            $cart = $this->init($id);
        }
        $cart->discountCode = $coupon->code;
        $cart->save();
        return response()->json('applied successfully');
    }
    public function applyAddress(Request $request, $id)
    {
        $userId = $request->user()->id;
        $address = Address::find($id);
        if ($address == null) {
            return response()->json('this address dosen\'t exists', 400);
        }
        $cart = Cart::where('userId', $userId)->cart()->first();
        if ($cart == null) {
            return response()->json('You Don\'t have cart yet', 400);
        }
        $cart->addressId = $id;
        $cart->shipping = $address->area->deliveryServiceTotal;
        $cart->save();
        return response()->json('applied successfully');
    }
    public function create(Request $request)
    {
        // dd(CartProduct::all());
        $id = $request->user()->id;
        $cart = Cart::where('userId', $id)->cart()->first();
        if ($cart == null) {
            $cart = $this->init($id);
        }
        $this->setProducts($cart->id, $request->product,  $request->qnt);
        return response()->json('added to cart successfully');
    }
    private function init($id)
    {
        $cart = Cart::create(['userId' => $id]);
        return $cart;
    }
    private function setProducts($cart, $product, $qnt)
    {
        // dd($product);
        $qnt = $qnt == null ? 1 : $qnt;
        $product = Product::where('id', $product)->first();
        $cartProduct = CartProduct::where('productId', $product->id)->where('cartId', $cart)->first();
        $optionId = null;
        $image = null;
        if ($cartProduct !==  null && $optionId === null) {
            $cartProduct->qnt = $cartProduct->qnt + $qnt;
            $cartProduct->save();
            return $product;
        }
        $rec = [
            "cartId" => $cart,
            "productId" => $product->id,
            "image" => $image,
            "price" => $product->price,
            "qnt" => $qnt,
        ];
        CartProduct::create($rec);
        return $product;
    }
    public function delete(Request $request, $id)
    {
        $userId = $request->user()->id;
        $cart = Cart::where('userId', $userId)->cart()->first();
        if ($cart == null) {
            return response()->json('no items on your cart');
        }
        $pr = CartProduct::where('cartId', $cart->id)->where('productId', $id)->first();
        $pr->deleted_at = now();
        $pr->save();
        // dd($pr);
        return response()->json('deleted from cart successfully');
    }
    public function update($id, Request $request)
    {
        $userId = $request->user()->id;
        $cart = Cart::where('userId', $userId)->cart()->first();
        $cp = CartProduct::where('cartId', $cart->id)->where('productId', $id)->first();
        if ($cp == null) {
            return response()->json(['message' => 'your cart dosen\'t contain this product'], 400);
        }
        DB::update(
            'UPDATE cart_product SET qnt = ? WHERE cartId = ? AND productId = ?',
            [
                $request->qnt,
                $cart->id,
                $id,
            ]
        );
        return response()->json('quantity updated successfully');
    }
}
