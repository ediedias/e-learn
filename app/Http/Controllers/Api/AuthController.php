<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Response;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\LoginUserRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([], Response::HTTP_CREATED);
    }

    public function login(LoginUserRequest $request)
    {
        $request->validated($request->all());        

        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['error' => 'Invalid Credentials'], Response::HTTP_UNAUTHORIZED);
        }

        $user = User::firstWhere('email', $request->email);

        return response()->json([
            'data' => new UserResource($user), 
            'meta' => [
                'token' => $user->createToken('Token ' . $user->email)->plainTextToken
            ]
        ], Response::HTTP_OK);
    }
}
