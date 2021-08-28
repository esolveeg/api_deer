<?php

namespace App\Http\Controllers;

use App\Address;
use App\Area;
use App\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    public function create(Request $request)
    {
        $address = $this->validateReq($request);
        if(gettype($address) != 'array'){
            return $address;
        }
        $address['userId'] = $request->user()->id;
        // dd($address);
        $address = Address::create($address);
        return response()->json($address->id);
    }
    public function update(Request $request , $id)
    {
        $currAddress = Address::find($id);
        if(!$currAddress){
            return response()->json('You are trying to edit none existing address' , 400);

        }   
        if($currAddress->userId !== $request->user()->id){
            return response()->json("this address dosen't belong to this user" , 400);
        }
        $address = $this->validateReq($request);
        if(gettype($address) != 'array'){
            return $address;
        }
        $address = Address::where('id', $id)->update($address);
        return response()->json(['success' => true , 'message' => 'address updated successfully']);
    }
    public function delete($id)
    {
        $address = Address::find($id);
        if(!$address){
            return response()->json('this address is not stored' , 400);
        }
        DB::delete('DELETE FROM addresses WHERE id = ? ' , [$id]);
        return response()->json(['success' => true , 'message' => 'address deleted successfully']);
    }
    public function find(Request $request , $id){
        // dd('hu');
        $address = Address::find($id);
    
        if(!$address){
            return response()->json("this address dosn't exist",400);
        }
        if($address->userId !== $request->user()->id){
            return response()->json("this address dosn't belong to this user",403);
        }
        $area = $address->area;
        $section = $area->parent ? $area->parent : $area ;
        $address->section = $section->id;
        $address->areas = $section->children;
        // dd($address);
        return $address;

    }
    public function list(Request $request)
    {
        $addresses = DB::select("SELECT  a.* , p.phone , ar.areaName , s.areaName sectionName , s.id sectionId  FROM addresses a JOIN phones p ON  a.phoneId = p.id JOIN areas ar ON a.areaId = ar.id JOIN areas s ON ar.sectionId = s.id  WHERE a.userId = ? ", [$request->user()->id]);
        $addresses = Address::where('userId' , $request->user()->id)->get();
        return $addresses;
    }

    private function validateReq($request)
    {
        $rules = [
            "buildingNo" => "required|max:255",
            "rowNo" => "required|max:255",
            "flatNo" => "required|max:255",
            "street" => "required|max:255",
            "remark" => "nullable|max:255",
            "main" => "nullable|max:1|min:1",
            "areaId" => "required",
            "phoneId" => "required",
            
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }
        request()->merge(['title' => $request->buildingNo. ' ' . $request->street. ' ' . $request->remark. ' ' . $request->rowNo. ' ' . $request->flatNo]);
        return $request->all();
    }
}
