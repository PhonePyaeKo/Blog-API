<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponse;

    //User Index
    public function index()
    {
        $users = User::with(['posts', 'comments', 'role'])->latest()->paginate(10);

        return $this->successResponse('Users Index', UserResource::collection($users));
    }

    //Detail
    public function show(User $user)
    {
        if(!$user) {
            return $this->errorResponse('User Not found!', 404);
        }

        $user->load([
            'role', 'posts', 'comments'
        ]);

        return $this->successResponse('User Detail', new UserResource($user));
    }
}
