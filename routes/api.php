<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Dashboard\CommentController as DashboardCommentController;
use App\Http\Controllers\Api\Dashboard\PostController as DashboardPostController;
use App\Http\Controllers\Api\Dashboard\UserController;
use App\Http\Controllers\Api\Posts\CommentController;
use App\Http\Controllers\Api\Posts\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Register & Login
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//middleware sanctum
Route::middleware('auth:sanctum')->group(function() {
    //logout
    Route::post('/logout', [AuthController::class, 'logout']);

    //Post CRUD
    Route::apiResource('posts', PostController::class);

    //Comment Api Route
    Route::apiResource('posts.comments', CommentController::class)->only(['store', 'update', 'destroy']);
});


//dashboard
Route::middleware(['auth:sanctum', 'can:access-dashboard'])->prefix('dashboard')->group(function() {
    //PostController
    Route::apiResource('posts', DashboardPostController::class);

    //Comment(create, update, destroy)
    Route::apiResource('comments', DashboardCommentController::class)->only([
        'index', 'show', 'destroy'
    ]);

    //comment Ban Unban
    Route::put('comments/{comment}/ban', [DashboardCommentController::class, 'ban']);
    Route::put('comments/{comment}/unban', [DashboardCommentController::class, 'unban']);

    //User
    Route::apiResource('users', UserController::class);
});
