<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    use ApiResponse;

    public function index()
    {
        //Post Index
        $posts = Post::with('user')->latest()->paginate(5);

        return $this->successResponse('Post Index', PostResource::collection($posts));
    }

    public function store(Request $request)
    {
        //Create Post

        //validate Data
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
        ]);

        //if validator fails
        if($validator->fails()) {
            return $this->errorResponse('Validation Fail', $validator->errors(), 422);
        }

        try{

            $post = Post::create([
                'title' => $request->title,
                'content' => $request->content,
                'user_id' => Auth::id(),
            ]);

            return $this->successResponse('Post Create Successfully', new PostResource($post->load('user')), 201);

        } catch(\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    //Post Detail
    public function show(Post $post)
    {
        if(!$post) {
            return $this->errorResponse('Post not found!', 404);
        }

        $post->load([
            'user', 'comments.user'
        ]);

        return $this->successResponse('Post Detail', new PostResource($post));
    }

    //Update Post
    public function update(Request $request, Post $post)
    {
        //validate data
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
        ]);

        if($validator->fails()) {
            return $this->errorResponse('Validation Error', $validator->errors(), 422);
        }

        try {
            //Update Post
            $post->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);

            return $this->successResponse('Post Updated Successfully', new PostResource($post->load('user')), 200);

        } catch(\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    //Delete Post
    public function destroy(Post $post)
    {
        //if no post
        if(!$post) {
            return $this->errorResponse('Post not found!', 404);
        }

        $post->delete();

        return $this->successResponse('Post Deleted');
    }
}
