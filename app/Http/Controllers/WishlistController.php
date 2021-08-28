<?php

namespace App\Http\Controllers;

use App\Cart;
use App\CartProduct;
use App\Product;
use App\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WishlistController extends Controller
{
    public function get(Request $request)
    {
        $id = $request->user()->id;
        $wishlist = DB::select(
                    "SELECT 
                        p.*
                        FROM wishlist w 
                        JOIN products p 
                            ON w.productId = p.id
                        WHERE w.userId = ? AND isNull(w.deleted_at) " , [$id]);
        
        if(count($wishlist) > 0){
            foreach($wishlist as $pr){
                $pr->ItemImage = $pr->ItemImage && file_exists('images/'.$pr->ItemImage) ? asset('images/' . $pr->ItemImage) : $pr->ItemImage;
            }
            return response()->json($wishlist);
        } else{
            return response()->json('no items');
        }   
    }

    public function create(Request $request)
    {
        $id = $request->user()->id;
        $item = Wishlist::where('productId' , $request->product)->where('userId' , $id)->first();
        if($item !== null){
            $item->delete();
            return response()->json('deleted');
        
        }
        $rec = ['userId' => $id , 'productId' => $request->product];
        Wishlist::create($rec);
        return response()->json('added to wishlist successfully');
    }

    public function delete(Request $request , $id)
    {
        $userId = $request->user()->id;
        $rec = Wishlist::where('userId' , $userId)->where('productId' , $id)->first();
        if($rec == null){ 
                return response()->json('Sorry! this item dosn\'t exist on your wishlist' , 400);
        } 
        $rec->delete();
        return response()->json('added to wishlist successfully');
    }


    public function switch(Request $request , $id)
    {
        $userId = $request->user()->id;
        $rec = Wishlist::where('userId' , $userId)->where('productId' , $id)->first();
        if($rec == null){ 
                return response()->json('Sorry! this item dosn\'t exist on your wishlist' , 400);
        } 
        $cart = Cart::where('userId' , $userId)->cart()->first();
        if($cart == null){
            $cart = $this->init($userId);
        }
        // dd($cart);
        $this->setProducts($cart->id , $id , $request->qnt);
        $rec->delete();
        return response()->json('switched to cart successfully');
    }

    private function init($id){
        $cart = Cart::create(['userId' => $id]);
        return $cart;
    }
    private function setProducts($cart  , $product , $qnt ){
        // dd($product);
        $qnt = $qnt == null ? 1 : $qnt;
        $product = Product::where('id' , $product)->first();
        $rec = [
            "cartId" => $cart,
            "productId" => $product->id,
            "price" => $product->POSPP,
            "qnt" => $qnt,
        ];
        // dd($rec);
        CartProduct::create($rec);
        return $product;
    }
}
