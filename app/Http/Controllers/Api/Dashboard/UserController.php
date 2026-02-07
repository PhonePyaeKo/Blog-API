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
        $users = User::with(['posts', 'comments'])->latest()->paginate(10);

        return $this->successResponse('Users Index', UserResource::collection($users));
    }
}
