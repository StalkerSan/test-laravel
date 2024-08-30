<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function register(AuthRegisterRequest $request)
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ];

        $data['password'] = bcrypt($request->password);

        $user = User::create($data);

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('passportToken')->accessToken
        ], 200);

    }

    public function login(Request $request)
    {
        $credentials = [
            'email'    => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials))
        {
            $token = Auth::user()->createToken('passportToken')->accessToken;

            return response()->json([
                'user' => Auth::user(),
                'token' => $token
            ], 200);
        }

        return response()->json([
            'error' => 'Unauthorised'
        ], 401);

    }
}
