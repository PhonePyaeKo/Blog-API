<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // trait ApiResponse Use
    use ApiResponse;

    //Register
    public function register(Request $request)
    {
        // Validate data from register form
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        // if Validation fails
        if($validator->fails()) {
            return $this->errorResponse('Validation Error', $validator->errors(), 422);
        }

        try {
            // Create User using User Model create method
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Create Token
            $token = $user->createToken('api-token')->plainTextToken;

            //Success Response
            return $this->successResponse('Register Successfully', 
            ['user' => $user, 'token' => $token,], 201);

        } catch(\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
