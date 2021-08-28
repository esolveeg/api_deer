<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Phone;
use App\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Client;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->where('type', 0)->first();
        if (!$user) {
            return response()->json("هذا البريد الالكتروني غير موجود", 400);
        }
        $rules = [
            'password' => 'required|max:255|min:6',
            'email' => 'required|max:255|email',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        if (!Hash::check($request->password, $user->password)) {
            return response()->json("كلمة المرور غير مطابقة لهذا الحساب", 400);
        }

        $tokenRequest = $this->loginAction($user->email, $request->password);
        return app()->handle($tokenRequest);
    }
    public function register(Request $request)
    {

        if (User::where('email', $request->email)->count() > 0) {
            return response()->json("عفوا  هئا البريد  موجود بالفعل", 400);
        }
        $rules = [
            'email' => 'required|email|max:255',
            'password' => 'required|max:255',
            'name' => 'required|max:255',
            'phone' => 'required|numeric|unique:phones'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $userRequest =  [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ];
        $user =  User::create($userRequest);
        $phoneRequest =  [
            'Phone' => $request->phone,
            'userId' => $user->id,
        ];
        $this->attachPhone($phoneRequest);

        if (!$user) return   response()->json("registration faild", 400);
        $tokenRequest = $this->loginAction($user->email, $request->password);


        return app()->handle($tokenRequest);
    }

    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response()->json("logged out successfully", 200);
    }
    protected function attachPhone($request)
    {
        $phone = Phone::create($request);
    }
    protected function loginAction($email, $passwrod)
    {
        
        $passwordGrantClient = Client::find(env('PASSPORT_CLIENT_ID', 2));
        // dd($email);

        // dd($passwordGrantClient);
        $data = [
            'grant_type' => 'password',
            'client_id' => $passwordGrantClient->id,
            'client_secret' => $passwordGrantClient->secret,
            'username' => $email,
            'password' => $passwrod,
            'scope' => '*',
        ];

        // dd($data);

        return  Request::create('oauth/token', 'post', $data);
    }
}
