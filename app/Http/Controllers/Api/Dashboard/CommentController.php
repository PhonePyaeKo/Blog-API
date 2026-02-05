<?php

namespace App\Http\Controllers\Api\Dashboard;

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
    //Index
    public function index()
    {
        $comments = Comment::with(['user','post'])->latest()->paginate(20);

        return $this->successResponse('Comments Index', CommentResource::collection($comments));
    }

    //Show
    public function show(Comment $comment)
    {
        //if no comment
        if(!$comment) {
            return $this->errorResponse('Comment not found!', 404);
        }

        //if comment 
        $comment->load([
            'post', 'post.user', 'user'
        ]);

        return $this->successResponse('Comment Detail', new CommentResource($comment), 200);
    }

    //Ban Comment
    public function ban(Comment $comment)
    {
        try {
            //ban comment
            $comment->update([
                'is_banned' => true,
            ]);

            return $this->successResponse('Comment Update Successfully', new CommentResource($comment->load('user')));

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    //Unban
    public function unban(Comment $comment)
    {
        try {
            //unban comment
            $comment->update([
                'is_banned' => false,
            ]);

            return $this->successResponse('Comment Update Successfully', new CommentResource($comment->load('user')));

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    //Delete
    public function destroy(Comment $comment)
    {
        try {
            $comment->load('post');

            //Delete comment
            $comment->delete();

            return $this->successResponse('Comment Delete Successfully');

        } catch (\Exception $e){
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
