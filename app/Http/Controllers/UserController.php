<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    private $loggedUser;

    public function __construct()
    {
        $this->middleware('auth:api', [
            'except' => [
                'store',
            ]
        ]);

        $this->loggedUser = Auth::user();
    }

    public function index()
    {
        //
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:200|min:6',
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


        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json('ocorreu erro');
        }

        $message = "usuário criado com sucesso $token";

        return response()->json($message, 200);
    }


    public function show(Request $request)
    {
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:200|min:6',
            'email' => 'string|email|unique:users|max:150',
            'password' => 'string|max:20|min:8',
            'passwordConfirm' => 'string|max:20|min:8|same:password',
            'birthdate' => 'date_format:Y-m-d',
            'city' => 'string|max:100|min:3',
            'work' => 'string|max:100|min:3',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = User::find($this->loggedUser['id']);

        if ($request->input('name')) {
            $user->name = $request->input('name');
        }
        if ($request->input('email')) {
            $user->email = $request->input('email');
        }
        if ($request->input('password')) {
            $user->password = Hash::make($request->input('password'));
        }
        if ($request->input('birthdate')) {
            $user->birthdate = $request->input('birthdate');
        }

        $user->save();
    }


    public function updateAvatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|mimes:jpeg,png,jpg'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $image = $request->file('avatar');

        $fileName = md5(time() . rand(0, 9999)) . '.jpg';

        $destPath = public_path('/media/avatars');

        $img = Image::make($image->path())
            ->fit(200, 200)
            ->save($destPath . '/' . $fileName);

        $user = User::find($this->loggedUser['id']);
        $user->avatar = $fileName;
        $user->save();

        return response()->json(url('/media/avatars/' . $fileName));
    }

    public function updateCover(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cover' => 'required|mimes:jpeg,png,jpg'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $image = $request->file('cover');

        $fileName = md5(time() . rand(0, 9999)) . '.jpg';

        $destPath = public_path('/media/covers');

        $img = Image::make($image->path())
            ->fit(850, 310)
            ->save($destPath . '/' . $fileName);

        $user = User::find($this->loggedUser['id']);
        $user->cover = $fileName;
        $user->save();

        return response()->json(url('/media/covers/' . $fileName));
    }
}
