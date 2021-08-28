<?php

namespace App\Http\Controllers\admin;

use App\Cart;
use App\Http\Controllers\Controller;
use App\QueryFilters\FromFilter;
use App\QueryFilters\ToFilter;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    private function handleListRequest($req){
        $req->show = !isset($req->show) ? 10 : intval($req->show);
        $req->page = !isset($req->page)  ? 1 : intval($req->page);
        $offset =   $req->show * ($req->page - 1);
        $req->offset = $offset;
       
        return $req;
    }
    public function list(Request $request)
    {
        $request = $this->handleListRequest($request);
        // dd(Cart::all());
        $pipeline = app(Pipeline::class)->send(Cart::query()->select(
            [
                'users.name AS user',
                'cart.id',
                'cart.status',
                'cart.shipping',
                'cart.discount_code',
                'cart.closed_at',
                'cart.created_at'
            ])
            ->join('users', 'user_id', '=', 'users.id')
            ->orderBy('created_at', 'DESC'))->through([
                FromFilter::class,
                ToFilter::class,
            ])->thenReturn();
        $count = $pipeline->count();
        $items = $pipeline->skip($request->offset)->take($request->show)->get();
        foreach($items as $item){
            // dd($item);
            $subtotal = DB::select("SELECT SUM(price * qty) subtotal FROM cart_product WHERE cart_id = ? AND deleted_at IS NULL" ,[ $item->id])[0]->subtotal;
            $item->subtotal = $subtotal;
        }
        // dd($items);
        // dd($count);
        return response()->json(['data' => $items , 'total' => $count]);
       
    }

}
