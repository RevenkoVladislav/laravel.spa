<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\PostImageController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/following_posts', [UserController::class, 'followingUnlikedPost']);
    Route::get('/users/liked_posts', [UserController::class, 'likedPosts']);
    Route::get('/users/{user}/posts', [UserController::class, 'show']);
    Route::post('/users/{user}/toggle_following', [UserController::class, 'toggleFollowing']);
    Route::post('/users/stats', [UserController::class, 'getStats']);


    Route::get('/posts', [PostController::class, 'index']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::post('/post_images', [PostImageController::class, 'store']);
    Route::post('/posts/{post}/toggle_like', [PostController::class, 'toggleLike']);
    Route::post('/posts/{post}/repost', [PostController::class, 'repost']);
    Route::post('/posts/{post}/comment', [PostController::class, 'storeComment']);
    Route::get('/posts/{post}/comment', [PostController::class, 'getComments']);
});
