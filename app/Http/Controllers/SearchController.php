<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{
    private $loggedUser;

    public function __construct(){
        $this->middleware('auth:api');

        $this->loggedUser = auth()->user();
    }
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $users = User::where('name', 'like', '%'.$request->text.'%')
        ->get();

        $usersList = [];

        foreach($users as $user){
            $usersList [] = [
                'id' => $user->id,
                'nome' => $user->name,
                'avatar' => url('media/avatars/'.$user->avatar)
            ];
        }

        return response()->json($usersList);
    }
}
