<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostLike;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostLikeController extends Controller
{
    private $loggedUser;

    public function __construct()
    {
        $this->middleware('auth:api');

        $this->loggedUser = auth()->user();
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        if (!Post::find($request->id)) return response()->json('post não exist');

        $newPostLike = new PostLike();
        $newPostLike->post_id = $request->id;
        $newPostLike->user_id = $this->loggedUser['id'];
        $newPostLike->created_at = date('Y-m-d H:i:s');
        $newPostLike->save();

        return response()->json('Like success');
    }

    public function destroy($id = false)
    {
        if ($id) {
            if (!Post::find($id)) return response()->json('post não existe');
        }else{
            return response()->json('insira um id de um post');
        }

        $postLike = PostLike::wherePostId($id)
            ->whereUserId($this->loggedUser['id'])
            ->first();

        if (!$postLike) return response()->json('like não existe');

        $postLike->delete();

        return response()->json('like deletado');
    }
}
