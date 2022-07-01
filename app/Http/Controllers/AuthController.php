<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            $fields = $request->validated();
    
            $fields['password'] = Hash::make($fields['password']);
            
            $user = User::create($fields);
    
            $accessToken = $user->createToken('access_token')->plainTextToken;
    
            $response = [
                'user' => [
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'accessToken' => $accessToken,
                ],
            ];
    
            return response()->json($response, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $fields = $request->validated();
            
            $user = User::where('username',$fields['username'])->first();
    
            if (!$user || !Hash::check($fields['password'], $user->password)) {
                return response()->json(['message' => 'Bad credentials'], 401);
            }

            $accessToken = $user->createToken('access_token')->plainTextToken;
    
            $response = [
                'user' => [
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'accessToken' => $accessToken,
                ],
            ];
    
            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function logout(Request $request)
    {
        try {
            $tokenId = explode(' ', $request->header()['authorization'][0])[1][0];

            $request->user()->tokens()->where('id', $tokenId)->delete();
            
            return response()->json(['message' => 'Logged out'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
