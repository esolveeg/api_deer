<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Product;
use App\ProductImage;
use App\QueryFilters\ByWeight;
use App\QueryFilters\GroupCode;
use App\QueryFilters\PriceFrom;
use App\QueryFilters\PriceTo;
use App\QueryFilters\Search;
use App\QueryFilters\Sort;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function get(Request $request)
    {
        // dd((int)$request->byWeight);
        $pipeline = app(Pipeline::class)->send(Product::query())->through([
            ByWeight::class,
            PriceFrom::class,
            PriceTo::class,
            Search::class,
            Sort::class,
            GroupCode::class,

        ])->thenReturn();
        $products = $pipeline->paginate(8);
        $products->getCollection()->transform(function ($pr) {
            $pr->ItemImage = $pr->ItemImage && file_exists('images/'.$pr->ItemImage) ? asset('images/' . $pr->ItemImage) : $pr->ItemImage;
            return $pr;
        });
        
        return $products;
    }

    public function upload(Request $request , $id)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            // 'images' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $product = Product::find($id);

    
        // dd($files=$request->file('images'));
    
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = $image->getClientOriginalName();
            $extension = $image->getClientOriginalExtension();
            $filename = $name . time() . '.' . $extension;
                      
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $filename);
            $product->ItemImage =$filename;
            $product->save();
        }  
        DB::delete("DELETE FROM product_images WHERE product_id = ?" , [$id]);
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image ){
                $extension = $image->getClientOriginalExtension();
                $name = $image->getClientOriginalName();
                $filename = $name .time() . '.' . $extension;
                $destinationPath = public_path('/images/products');
                $image->move($destinationPath, $filename);

                ProductImage::create([
                    'image' => $filename,
                    'product_id' => $id
                ]);
            }
        }

        
    
        return response()->json('Image uploaded successfully'); 
    }

}
