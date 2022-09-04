<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api',[
            'except'=>[
                'login',
                'unauthorized'
            ]
        ]);
    }

    public function unauthorized(){
        return response()->json('não autorizado',401);
    }
    
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [            
            'email' => 'required|string|email|max:150',
            'password' => 'required|string|max:20|min:8',
        ]);
 
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        
        $token = Auth::attempt($validator->validate());

        $user = Auth::user();

        return response()->json([
                'status' => 'success',
                'user' => $user,
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);

    }
    
    public function logout()
    {
               
        Auth::logout();        

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

   
    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorization' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    
    public function show($id)
    {
        //
    }

   
    public function edit($id)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
        //
    }    
    
}
