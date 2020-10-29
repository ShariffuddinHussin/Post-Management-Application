<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|min:3',
            'email' => 'required|email|unique:user',
            'password'=>'required|min:6'
        ]);

        $user = User::create([
            'name' => $request ->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $token = $user->createToken('post')->accessToken;
 
        return response()->json(['token' => $token], 200);
    }
}
