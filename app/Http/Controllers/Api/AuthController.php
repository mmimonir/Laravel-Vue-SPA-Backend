<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Client;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(UserLoginRequest $request)
    {
        $passwordGrantClient = Client::where('password_client', 1)->first();
        $data =[
            'grant_type'=>'password',
            'client_id'=>$passwordGrantClient->id,
            'client_secret'=>$passwordGrantClient->secret,
            'username'=>$request->email,
            'password'=>$request->password,
            'scope'=>'*'

        ];
        $tokenRequest = Request::create('/oauth/token', 'post', $data);
        $tokenResponse = app()->handle($tokenRequest);
        $contentString = $tokenResponse->content();
        $tokenContent = json_decode($contentString, true);

        if (!empty($tokenContent['access_token'])) {
            return $tokenResponse;
        }
        return response()->json(['message'=>'Unauthenticated']);
    }
    // public function login(Request $request)
    // {
    //     $validatedData = $request->validate([
    //     'email' => 'required|email',
    //     'password' => 'required',
    // ]);

    //     $email = $request->email;
    //     $password = $request->password;

    //     $user = User::where('email', '=', $email)->first();

    //     if (!$user) {
    //         return response()->json(['success'=>false, 'message' => 'Login Fail, please check email id']);
    //     }
    //     if (!Hash::check($password, $user->password)) {
    //         return response()->json(['success'=>false, 'message' => 'Login Fail, pls check password']);
    //     }
    //     $token = $user->createToken('Laravel')->accessToken;
    //     return response()->json(['token' => $token], 200);
    // }



    public function register(UserRegisterRequest $request)
    {
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);
        if (!$user) {
            return response()->json(["success"=>false, "message"=>"Registration Failed"]);
        }
        return response()->json(["success"=>true, "message"=>"Registration Succeeded"]);
    }
}
