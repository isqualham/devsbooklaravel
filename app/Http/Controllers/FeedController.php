<?php

namespace App\Http\Controllers;

use App\Models\Post;
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

    public function index()
    {
        //
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
            }
            
            return response()->json('digite um texto');
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
                    
                $post->body = $fileName;
            }
            return response()->json('insira uma imagem');            
        }
        
        $post->user_id = $this->loggedUser['id'];
        $post->type = $request->input('type');
        $post->created_at =  date('Y-m-d H:i:s');
        $post->save();
    }

   
    public function show($id)
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
