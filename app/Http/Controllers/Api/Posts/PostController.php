<?php

namespace App\Http\Controllers\Api\Posts;

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

    //Index
    public function index(Request $request)
    {
        $posts = Post::with('user')->latest()->paginate(5);

        return $this->successResponse('Post Index', PostResource::collection($posts));
    }

    //Show
    public function show(Request $request, Post $post)
    {
        //if no post
        if(!$post) {
            return $this->errorResponse('Post not found!', 404);
        }

        //if post 
        $userPost = $post->load([
            'user', 'comments.user'
        ]);

        return $this->successResponse('Post Detail', new PostResource($userPost), 200);
    }

    //Store
    public function store(Request $request)
    {
        //Validate Form Data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required',
        ]);

        //if validator fails
        if($validator->fails()) {
            return $this->errorResponse('Validation Error', $validator->errors(), 422);
        }

        try {
            //create post
            $post = Post::create([
                'title' => $request->title,
                'content' => $request->content,
                'user_id' => Auth::id(),
            ]);

            return $this->errorResponse('Post Create Successfully', new PostResource($post->load('user')), 201);
            
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    //Update
    public function update(Request $request, Post $post)
    {
        //if no post
        if(!$post) {
            return $this->errorResponse('Post not found!', 404);
        }

        // if post, validate data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required',
        ]);

        //if validator fails
        if($validator->fails()) {
            return $this->errorResponse('Validation Error', $validator->errors(), 422);
        }

        //Update Data
        $post->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return $this->successResponse('Post Update Successfully', new PostResource($post->load('user')), 200);
    }
}
