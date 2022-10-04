<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostLike;
use App\Models\User;
use App\Models\UserRelation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class FeedController extends Controller
{
    private $loggedUser;

    public function __construct()
    {
        $this->middleware('auth:api');

        $this->loggedUser = auth()->user();
    }

    public function index(Request $request){

        $user = [];
        $userList = UserRelation::whereUserFron($this->loggedUser['id'])
            ->get();

        foreach($userList as $user){
            $user [] = $user->user_to;
        }

        $user [] = $this->loggedUser['id']; 

        $postList = Post::whereIn('user_id', $user)
            ->orderBy('created_at', 'desc')
            ->paginate();

        $posts = $this->postListToObject($postList, $this->loggedUser['id']);

        return response()->json($posts);
    }

    private function postListToObject($postList, $loggedUser){
        foreach($postList as $postKey => $postItem){
            if($postItem->user_id == $loggedUser){
                $postList[$postKey]['mine'] = true;
            }else{
                $postList[$postKey]['mine'] = false;
            }

            $userInfo = User::find($postItem->user_id);
            $postList[$postKey]['user'] = $userInfo;

            $likes = PostLike::wherePostId($postItem->id)->count();
            $postList[$postKey]['likeCount'] = $likes;

            $isLiked = PostLike::wherePostId($postItem->id)
                ->whereUserId($loggedUser)
                ->count();
            
            $postList[$postKey]['liked'] = ($isLiked >0) ? true : false;

            $comments = PostComment::wherePostId($postItem->id)->get();

            foreach($comments as $commentKey => $comment){
                $user = User::find($comment->user_id);
                $comments[$commentKey]['user'] = $user;
            }

            $postList[$postKey]['comments'] = $comments;            
        }
        
        return $postList;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => [
                'required',
                'string',
                Rule::in(['text', 'photo']),
            ],
            'text' => 'string|max:255',
            'photo' => 'mimes:jpg,png,jpeg',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $post = new Post();

        if($request->input('type') == 'text'){
            if($request->input('text')){
                $post->body =  $request->input('text');
            }else{return response()->json('digite um texto');}
        }

        if($request->input('type') == 'photo'){
            if($request->file('photo')){
                $fileName = md5(time() . rand(0, 9999)) . '.jpg';
        
                $destPath = public_path('/media/uploads');
        
                $img = Image::make($request->file('photo')->path())
                    ->resize(800, null, function($constraint){
                        $constraint->aspectRatio();
                    })
                    ->save($destPath . '/' . $fileName);
                    
                $post->body = url('/media/uploads/' . $fileName);
            }else{return response()->json('insira uma imagem');}
        }
        
        $post->user_id = $this->loggedUser['id'];
        $post->type = $request->input('type');
        $post->created_at =  date('Y-m-d H:i:s');
        $post->save();
    }

}