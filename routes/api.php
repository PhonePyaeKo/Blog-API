<?php

use App\Http\Controllers\Api\Auth\AuthController;
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
    Route::prefix('posts/{post}')->group(function() {
        //Store 
        Route::post('/comments', [CommentController::class, 'store']);

        //Update 
        Route::put('/comments/{comment}', [CommentController::class, 'update']);

        //Delete 
        Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
    });
});
