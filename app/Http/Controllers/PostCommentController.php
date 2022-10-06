<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostCommentController extends Controller
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
            'id' => 'required|integer',
            'text' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        if (!Post::find($request->id)) return response()->json('post não exist');

        $postComment = new PostComment();
        $postComment->post_id = $request->id;
        $postComment->user_id = $this->loggedUser['id'];
        $postComment->created_at =  date('Y-m-d H:i:s');
        $postComment->body = $request->text;
        $postComment->save();

        return response()->json('postComment success');
    }    
}
