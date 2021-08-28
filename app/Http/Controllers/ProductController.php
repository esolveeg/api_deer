<?php

namespace App\Http\Controllers;

use App\Author;
use App\Cart;
use App\CartProduct;
use App\Group;
use App\Http\Requests\ListProductRequest;
use App\Product;
use App\QueryFilters\AuthorFilter;
use App\QueryFilters\ByWeight;
use App\QueryFilters\PriceTo;
use App\QueryFilters\PriceFrom;
use App\QueryFilters\Search;
use App\QueryFilters\GroupId;
use App\QueryFilters\Sort;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    //find product
    // attach group
    public function find($id, Request $request)
    {
        $product = Product::find($id);
        if ($product == null) {
            return response()->json('عفوا هئا المنتج غير موجود', 400);
        }
        $product = $this->loadProductMetaData($product);
        

        return response()->json($product);
    }

    private function loadProductMetaData($product){
        $group = Group::select('id', 'groupName', 'groupId')->find($product->groupId);
        $parent = $group->groupId ? Group::select('id', 'groupName')->find($group->groupId) : null;
        $product->groups = $group;
        $product->groups =  $parent == null ? [$group] : [$parent , $group];
        $author = Author::select('id', 'authorName')->find($product->authorId);
        $product->author = $author;

        return $product;
    }
    public function list(ListProductRequest $request)
    {


        $pipeline = app(Pipeline::class)->send(Product::query())->through([
            PriceFrom::class,
            PriceTo::class,
            Search::class,
            Sort::class,
            GroupId::class,
            AuthorFilter::class

        ])->thenReturn();
        $limit = $request->limit ? $request->limit : 8;
        $products = $pipeline->paginate($limit);
        foreach ($products as $product) {
            $product = $this->loadProductMetaData($product);
        }
        // dd($products);
        return $products;
    }



    public function listHome($key)
    {
        if ($key == 'featured') {
            $products = Product::where('featured', 1)->get();
        } else if ($key == 'latest') {
            $products = Product::where('latest', 1)->get();
        } else if ($key == 'bestseller') {
            $products = Product::where('bestseller', 1)->get();
            // dd($products);
        } else {
            return [];
        }
        return $products;
    }

    protected function inCart($user, $products, $request)
    {
        $cart = Cart::cart()->select(['id'])->where('userId', $user)->first();
        foreach ($products as $product) {
            if ($product->hasOptions) {
                $product = $this->productOptions($request, $product);
            }

            if ($cart !== null) {
                $inCart = CartProduct::where('cartId', $cart->id)->where('productId', $product->id)->first();
                if ($inCart !== null) {
                    $product->inCart = true;
                    $product->cartQty = $inCart->qty;
                }
            }
            $wihslist =  DB::select(
                "SELECT 
                        w.id
                        FROM wishlist w 
                        JOIN products p 
                            ON w.productId = p.id
                        WHERE w.userId = ? AND isNull(w.deleted_at) AND p.id = ? ",
                [$user, $product->id]
            );
            if (isset($wihslist[0])) {
                $product->inWihslit = true;
            }
            // dd($product);
        }

        return $products;
    }
}
