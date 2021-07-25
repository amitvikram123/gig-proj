<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    protected function validator(Request $request) 
    {
        return Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'mobile' => 'string|min:10',
            'age' => 'required|numeric',
            'firstname' => 'required|string|min:4|max:60',
            'lastname' => 'required|string|min:4|max:60',
            'gender' => ['required', Rule::in(['m','f','o'])],
        ]);
    }

    public function register(Request $request) 
    {
        $validationRules = $this->validator($request);
        if ($validationRules->fails()) {
            return response()->json($validationRules->errors()->toJson());
        }
        
        $newUser = $request->all();
        $newUser['password'] = Hash::make($newUser['password']);
        $user = User::create($newUser);

        $token = JWTAuth::fromUser($user);
        return response()->json(compact('user','token'), 201);
    }
}
