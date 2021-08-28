<?php

namespace App\Http\Controllers;

use App\Author;
use App\Banner;
use App\Setting;
use Illuminate\Http\Request;

class GlobalController extends Controller
{
    public function getSliders()
    {
        return Banner::where('type' , 0)->get();
    }
    public function listAuthors(Request $request)
    {
        $authors = Author::select("id" , "authorName" , "topAuthor");
        return $request->top == 1 ?  $authors->top()->get() : $authors->get();
    }

    public function getHomeBanners()
    {
        return Banner::where('type' , 1)->get();
    }


    public function getSettings()
    {
        $settings = Setting::get();
        $val = [];
        foreach($settings as $setting){
            $val[$setting->key] = $setting->value;
            $val[$setting->key.'_ar'] = $setting->value_ar;
        }
        return $val;
    }


    public function findSetting($key)
    {
        return Setting::where('key' , $key)->first();
    }
}
