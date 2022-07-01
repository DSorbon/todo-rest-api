<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $fields = $request->validate([
                'username' => ['required', 'string', 'unique:users', 'min:3', 'max:50'],
                'email' => ['required', 'string', 'email', 'unique:users'],
                'password' => ['required', 'string', 'confirmed', 'min:6', 'max:50'],
            ]);
    
            $fields['password'] = Hash::make($fields['password']);
            
            $user = User::create($fields);
    
            $accessToken = $user->createToken('access_token')->plainTextToken;
    
            $response = [
                'status' => 'Success',
                'message' => 'User created',
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

    public function login(Request $request)
    {
        try {
            $fields = $request->validate([
                'username' => ['required', 'string', 'min:3', 'max:50'],
                'password' => ['required', 'string', 'min:6', 'max:50'],
            ]);
    
            // $hashedPassword = Hash::make($fields['password']);
            
            $user = User::where('username',$fields['username'])->first();
    
            if (!$user || !Hash::check($fields['password'], $user->password)) {
                return response()->json(['message' => 'Bad credentials'], 401);
            }

            $accessToken = $user->createToken('access_token')->plainTextToken;
    
            $response = [
                'message' => 'Success',
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
