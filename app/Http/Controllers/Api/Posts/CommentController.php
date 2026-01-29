<?php

namespace App\Http\Controllers\Api\Posts;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    use ApiResponse;

    //Create comment
    public function store(Request $request, Post $post)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required',
        ]);

        //if validator fails
        if($validator->fails()) {

            return $this->errorResponse('Validation Error', $validator->errors(), 422);

        }

        try {
            //create comment belongsTo Post
            $comment = $post->comments()->create([
                'comment' => $request->comment,
                'user_id' => Auth::id(),
            ]);

            return $this->successResponse('Comment Created Successfully', new CommentResource($comment->load('user')), 201);

        } catch(\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    //Update
    public function update(Request $request, Comment $comment)
    {
        //Unauthorized
        if($comment->user_id !== Auth::id()) {
            return $this->errorResponse('Unauthorized Action', 403);
        }

        //validate data
        $validator = Validator::make($request->all(), [
            'comment' => 'required',
        ]);

        if($validator->fails()) {
            return $this->errorResponse('Validation Error', $validator->errors(), 422);
        }

        try {
            //Update comment
            $comment->update([
                'comment' => $request->comment,
            ]);

            return $this->successResponse('Comment Update Successfully', new CommentResource($comment->load('user')));

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
