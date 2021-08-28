<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Phone;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Client;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $this->catchUserFromReq($request);
        if(gettype($data) != 'array'){
            return $data;
        }
        $rules = $data['rules'];
        $user = $data['user'];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }
        if (!Hash::check($request->password, $user->password)) {
            return response()->json("This password dosen't belong to this email",400);
        }
     
        $tokenRequest = $this->loginAction($user , $request->password);
        
        
        return app()->handle($tokenRequest);


    }
    public function register(Request $request)
    {

        if(User::where('email' , $request->email)->count() > 0){
            return response()->json("this email is already exists",400);
           
            
        }
        $rules = ['email' => 'required|email|max:255',
        'password' => 'required|max:255',
        'name' => 'required|max:255',
        'phone' => 'required|max:255|unique:phones'];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }
    
        $userRequest =  [
            'name' =>$request->name,
            'email' =>$request->email,
            'password' =>bcrypt($request->password),
        ];
        $user =  User::create($userRequest);
        $phoneRequest =  [
            'Phone' =>$request->phone,
            'AccSerial' =>$user->id,
        ];
        $this->attachPhone($phoneRequest);
        
        if(!$user) return   response()->json("thisregistration_faild",400);
        $tokenRequest = $this->loginAction($user , $request->password);
        
        
        return app()->handle($tokenRequest);
    }

    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key){
            $token->delete();
        });

        return response()->json("logged out successfully",200);

    }
    protected function attachPhone($request){
        $phone = Phone::create($request);
    }
    protected function catchUserFromReq($request){
        $rules = [
            'password' => 'required|max:255|min:6',
            'email' => 'required|max:255|email',
        ];
        $user = User::where('email' , $request->email)->where('type' , 1)->first();
        if(!$user){
            return response()->json("this email is not found",400);
        }
        return ['user' => $user , 'rules' => $rules];
       
    }

    protected function loginAction($user , $passwrod){
        $passwordGrantClient = Client::find(env('PASSPORT_CLIENT_ID', 2));
        
        // dd($passwordGrantClient);
        $data = [
            'grant_type' => 'password',
            'client_id' => $passwordGrantClient->id,
            'client_secret' => $passwordGrantClient->secret,
            'username' => $user->email,
            'password' => $passwrod,
            'scope' => '*',
        ];

        return  Request::create('oauth/token' , 'post', $data );
    }
}
