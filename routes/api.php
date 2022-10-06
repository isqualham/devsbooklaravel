<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRelationController;
use Illuminate\Support\Facades\Route;


Route::get('/401', [AuthController::class, 'unauthorized'])->name('login');

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout']);
Route::post('/auth/refresh', [AuthController::class, 'refresh']);


Route::post('/user', [UserController::class, 'store']);
Route::put('/user', [UserController::class, 'update']);
Route::post('/user/avatar', [UserController::class, 'updateAvatar']);
Route::post('/user/cover', [UserController::class, 'updateCover']);

Route::post('/post', [PostController::class, 'store']);
Route::get('/post', [PostController::class, 'index']);

Route::get('/post', [PostController::class, 'show']);
Route::get('/post/photos', [PostController::class, 'photos']);
Route::get('/post/{id}', [PostController::class, 'show']);


Route::get('/user', [UserController::class, 'index']);
Route::get('/user/{id}', [UserController::class, 'show']);

Route::post('/userRelation', [UserRelationController::class, 'like']);

Route::get('/userRelation', [UserRelationController::class, 'index']);

Route::post('/postLike', [PostLikeController::class, 'store']);
Route::delete('/postLike/{id}', [PostLikeController::class, 'destroy']);

Route::post('/postComment', [PostCommentController::class, 'like']);

Route::get('/search', [SearchController::class, 'search']);