<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{


    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('post')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('post')->accessToken;
            return response()->json([
                'success' => 'Authorised',
                'token' => $token
            ], 200);
        } else {
            $user = User::where('email', '=', $request->email)->first();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Login Fail, please check email']);
            }
            if (!Hash::check($request->password, $user->password)) {
                return response()->json(['success' => false, 'message' => 'Login Fail, please check password']);
            }
            // dd(Hash::make($request->password));

        }
    }


    public function details()
    {
        return response()->json(['user' => auth()->user()], 200);
    }
}
