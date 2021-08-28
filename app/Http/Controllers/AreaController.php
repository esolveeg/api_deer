<?php

namespace App\Http\Controllers;

use App\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
{
    public function list(Request $request)
    {
        $areas = Area::select(['areaName' , 'id'])->active();
        if($request->sectionId){ 
            $areas = $areas->where('sectionId' , $request->sectionId);
        } else {
            $areas = $areas->main();
        }
        return $areas->get();
    }
    public function create(Request $request)
    {
        $area = $this->validateReq($request);
        if(gettype($area) != 'array'){
            return $area;
        }
        $area = Area::create($area);
        return response()->json(['success' => 'true' , 'message' => 'area created successfully']);
    }
    public function update(Request $request , $id)
    {
        $currArea = Area::find($id);
        if(!$currArea){
            return response()->json('You are trying to edit none existing area' , 400);

        }   
        $area = $this->validateReq($request);
        if(gettype($area) != 'array'){
            return $area;
        }
        $area = Area::where('id' , $id)->update($area);
        return response()->json('area updated successfully');
    }

    public function delete($id)
    {
        $area = Area::find($id);
        if(!$area){
            return response()->json('this area is not stored' , 400);
        }
        DB::delete('DELETE FROM araes WHERE id = ? ' , [$id]);
        DB::insert('call SetQuery(?)',["DELETE FROM OlAreas WHERE AreaNo = $id"]);
        return response()->json('area deleted successfully');
    }

    private function validateReq($request)
    {
        $rules = [
            "areaName" => "required|max:255",
            "deliveryServiceTotal" => "required|regex:/^\d+(\.\d{1,2})?$/|max:8",
            "postalCode" => "nullable|max:255",
            "apply" => "nullable|max:1",
            "sectionId" => "nullable|max:20",
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }
        if(isset($request->SectionNo) && !Area::find($request->SectionNo))
        {
            return response()->json('this parent area is not stored' , 400);
        }
        return $request->all();
    }

}
