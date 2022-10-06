<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRelation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserRelationController extends Controller
{
    private $loggedUser;

    public function __construct()
    {
        $this->middleware('auth:api');

        $this->loggedUser = auth()->user();
    }

    public function index(){
        
        $followings = UserRelation::whereUserFron($this->loggedUser['id'])->get();
        $followers = UserRelation::whereUserTo($this->loggedUser['id'])->get();

        $array ['seguindo'] = [];
        $array ['seguidores'] = [];
        
        foreach($followings as $following){
            $user = User::find($following->user_to);
            $array ['seguindo'][] =[
                'id' =>$user->id,
                'nome' => $user->name,
                'avatar' => url('media/avatars'.$user->avatar)
            ];
        }

        foreach($followers as $follower){
            $user = User::find($follower->user_fron);
            $array ['seguidores'][] =[
                'id' =>$user->id,
                'nome' => $user->name,
                'avatar' => url('media/avatars'.$user->avatar)
            ];
        }

        return response()->json($array);

    }


    public function like(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        if ($request->id == $this->loggedUser['id'])
            return response()->json('você não pode seguir você mesmo');

        if (!User::find($request->id))
            return response()->json('esse usuário não existe');

        $userRelation = UserRelation::whereUserFron($this->loggedUser['id'])
            ->whereUserTo($request->id)
            ->first();

        if($userRelation){
            $userRelation->delete();
            return response()->json('deslike success');
        }

        UserRelation::create(
            [
                'user_fron' => $this->loggedUser['id'],
                'user_to' => $request->id
            ]
        );

        return response()->json('like success');
    }
}
