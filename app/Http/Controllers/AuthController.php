<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $token = $user->createToken('post')->accessToken;
        return response(['token' => $token], 200);
    }

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];
        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('post')->accessToken;
            return response(['success' => true, 'token' => $token], 200);
        } else {
            $user = User::where('email', '=', $request->email)->first();
            if (!$user) {
                return response(['success' => false, 'message' => 'Email invalid'], 401);
            }
            if (!Hash::check($request->password, $user->password)) {
                return response(['success' => false, 'message' => 'Password invalid'], 401);
            }
        }
    }

    public function details()
    {
        return response(['user' => auth()->user()], 200);
    }
}
