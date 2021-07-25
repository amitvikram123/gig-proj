<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->guard = 'api';
    }

    protected function validator(array $credentials)
    {   
        return Validator::make($credentials, [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
        ]);
    }

    public function login(Request $request)
    {   
        $credentials = $request->only('email', 'password');
        $this->validator($credentials);
        try {
            $user = User::where([
                'email' => $credentials['email'],
                'password' => Hash::make($credentials['password']),
            ]);
            if (! $token = JWTAuth::attempt($credentials)) {
                dd($token);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid email or password',
                ], 403);
            }
        }
        catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }

    public function logout(Request $request) {
        $token = $request->header('Authorization');
        JWTAuth::parseToken()->invalidate($token);
        return response()->json(['logged_out']);
    }

    
}
