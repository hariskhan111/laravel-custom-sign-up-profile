<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name' => 'required | string | max:255',
            'email' => 'required | string | email | unique:users',
            'password' => 'required'
        ]);
        
        $user =  User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'is_admin' => $request->input('is_admin'),
            'password' => Hash::make($request->input('password')),
        ]);

        return response()->json([
            'response' => 'Register Successfully, please verify your email'
        ]);
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $credentials = request(['email','password']);
        if(!Auth::attempt($credentials)){
            return response()->json(['response'=>'unauthorized'],401);
        }

        $user = $request->user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user'=> Auth::user(),
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
