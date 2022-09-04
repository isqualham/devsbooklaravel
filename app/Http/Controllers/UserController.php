<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $loggedUser;

    public function __construct(){
        $this->middleware('auth:api',[
            'except'=>[
                'store',
            ]
        ]);
            
        $this->loggedUser = auth()->user();
    }
    
    public function index()
    {
        //
    }

           
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:200',
            'email' => 'required|string|email|unique:users|max:150',
            'password' => 'required|string|max:20|min:8',
            'birthdate' => 'required|date_format:Y-m-d'
        ]);
 
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->birthdate = $request->input('birthdate');        
        $user->save();

        
        $credentials = $request->only('email','password');

        $token = Auth::attempt($credentials);

        if(!$token){
            return response()->json('ocorreu erro');
        }

        $message = "usuário criado com sucesso $token";

        return response()->json($message, 200);
    }

   
    public function show(Request $request)
    {
       
    }

   
    public function edit($id)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
        //
    }

   
    public function destroy($id)
    {
        //
    }
}
