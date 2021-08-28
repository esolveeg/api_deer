<?php

namespace App\Http\Controllers;

use App\Group;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    public function create(Request $request)
    {
        $group = $this->validateReq($request);
        if(gettype($group) != 'array'){
            return $group;
        }
        $group = Group::create($group);
        return response()->json(['success' => true , 'message' => 'Group created successfully']);


    }
    public function update(Request $request , $id)
    {
        $group = $this->validateReq($request);
        if(gettype($group) != 'array'){
            return $group;
        }
        $group = Group::where('id' , $id)->update($group);
        return response()->json(['success' => true , 'message' => 'Group update successfully']);
    }
    public function disable($id)
    {
        $group = Group::find($id);
        if(!$group){
            return response()->json('You are trying to disable none existing group' , 400);
        }
        $group->Active = false;
        $group->save();
        return response()->json(['success' => true , 'message' => 'Group disabled successfully']);
    }
    public function enable($id)
    {
        $group = Group::find($id);
        if(!$group){
            return response()->json('You are trying to enable none existing group' , 400);
        }
        if($group->Active){
            return response()->json('This group is already enabled' , 400);
        }
        $group->Active = true;
        $group->save();
        return response()->json(['success' => true , 'message' => 'Group enabled successfully']);
    }
    public function delete($id)
    {
        $address = Group::find($id);
        if(!$address){
            return response()->json('this group is not stored' , 400);
        }
        DB::delete('DELETE FROM groups WHERE id = ? ' , [$id]);
        return response()->json(['success' => true , 'message' => 'groups deleted successfully']);
    }
    public function find($id)
    {
        $group = Group::find($id);
        if(!$group){
            return response()->json("this group dosn't exist",400);
        }
        $group['groups'] = $group->groups;
        return $group;
    }
    public function list(Request $request)
    {
        $groups = Group::select('id' , 'icon' , 'groupName')->active();
        // dd(Group::get());
        if($request->featured){
            return $groups->where('featured' , true)->get();
        }
        if($request->home){
            $groups = $groups->where('home' , true)->with('products')->get();
            
            return $groups;
        }
        if($request->groupId){
            $isNull = count($groups->where('groupId' , $request->groupId)->get()) === 0;
            // dd(!$isNull);
            if(!$isNull){
                return $groups->where('groupId' ,  $request->groupId)->get();
            } else {
                return Group::select('id' , 'groupName', 'icon')->active()->main()->get();
            }
        }
        // dd(count($groups->main()->get()));
        // dd(DB::select("SELECT count(*) FROM groups WHERE groupId IS NULL"));

        return $groups->main()->get();
    }

    private function validateReq($request)
    {
        $rules = [
            "groupName" => "required|max:255",
            "groupId" => "nullable|max:20",
            "Active" => "boolean",
        ];

        
        
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }
        if($request->groupId){
            $group = Group::find($request->groupId);
            if(!$group){
                return response()->json('this fathe group is not stored' , 400);
            }
        }
        return $request->all();
    }
    public function listWithChildren()
    {
        $groups = Group::select('id' ,'groupName')->main()->with('groups')->get();
        return $groups;
    }

    public function listThreeLayers()
    {
        $groups = Group::select('id' ,'groupName')->main()->with('groups')->get();
        // $groups = DB::select("SELECT id , groupName , groupId FROM groups")
        foreach($groups as $group){
            if(isset($group->children) && count($group->children) > 0){
                foreach ($group->children as $sub){
                    if(isset($group->children) && count($sub->children) > 0){
                        $group['hasThree'] = true;
                        $sub['children'] = $sub->children;
                    } else {
                        $group['hasTwo'] = true;
                    }
                }
            }
        }
        return $groups;
    }

}
