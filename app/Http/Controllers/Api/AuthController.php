<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function registration(Request $request) {
        $data = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|same:password|min:8',
        ]);

        if ($data->fails()) {
            return response()->json([
                'errors' => $data->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        $user = User::where('email', $request['email'])->firstOrFail();

        return response()->json([
            'access_token' => $token,
            'user'=>$user,
            'token_type' => 'Bearer',
        ], 200);
    }

    public function login(Request $request){
        $data = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($data->fails()) {
            return response()->json([
                'errors' => $data->errors()
            ], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }
        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }
    public function user(Request $request){
        return $request->user();
    }
}
