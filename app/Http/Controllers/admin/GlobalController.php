<?php

namespace App\Http\Controllers\admin;

use App\Banner;
use App\Group;
use App\Http\Controllers\Controller;
use App\QueryFilters\BannerType;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;

class GlobalController extends Controller
{
    public function delete($table , $id)
    {
        $record = DB::table($table)->where('id' , $id);
        if(count($record->get()) == 0){
            return response()->json('Sorry !this record can\'t be found');
        }
        $record->delete();
        return response()->json('Deleted Sucessfully');

    }

    public function getSettings(Request $request)
    {
        $pipeline = app(Pipeline::class)->send(Setting::query())->through([])->thenReturn();
        $settings = $pipeline->paginate(8);
        
        
        return $settings;
    }
    public function findSetting(Request $request , $id)
    {
        return Setting::find($id);
        // dd($request->all());
    }

    public function updateSetting(Request $request , $id)
    {
        $rec = Setting::find($id);
        if($rec->type === 'image'){
            //implement upload
            $this->validate($request, [
                'value' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                ]);
            if ($request->hasFile('value')) {
                $image = $request->file('value');
                $name = $image->getClientOriginalName();
                $extension = $image->getClientOriginalExtension();
                $filename = $name . time() . '.' . $extension;
                          
                $destinationPath = public_path('/images');
                $image->move($destinationPath, $filename);
                $rec->value =$filename;
                $rec->value_ar =$filename;
                $rec->save();

                return $rec;
            }  
        }
        $this->validate($request, [
            'value' => 'required',
            'value_ar' => 'required',
        ]);
        $rec->value = $request->value;
        $rec->value_ar = $rec->type == 'textarea' ? $request->value_ar : $request->value;
        $rec->save();

        return $rec;
        // dd($request->all());
    }
    public function banners()
    {
        $pipeline = app(Pipeline::class)->send(Banner::query())->through([
            BannerType::class

        ])->thenReturn();
        $banners = $pipeline->paginate(8);
        
        return $banners;
    }

    public function createBanner(Request $request , $type)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            // 'images' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        // dd($files=$request->file('images'));
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = $image->getClientOriginalName();
            $extension = $image->getClientOriginalExtension();
            $filename = $name . time() . '.' . $extension;
                      
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $filename);
            $request->image =$filename;
        }  
        $banner = Banner::create(['image' => $request->image , 'type' => $type]);
        return $banner;
    }

    public function editBanner(Request $request , $id)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            // 'images' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $banner = Banner::find($id);
        if($banner == null){
            return response()->json('this banner not found' , 400);
        }
        // dd($files=$request->file('images'));
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = $image->getClientOriginalName();
            $extension = $image->getClientOriginalExtension();
            $filename = $name . time() . '.' . $extension;
                      
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $filename);
            $banner->image =$filename;
            $banner->save();
        }  
        return $banner;
    }
    public function groups(){
        $group = Group::select([
            "id","GroupNameEn","GroupName"
        ])->get();
        return $group;
    }
}
