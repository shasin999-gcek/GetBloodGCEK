<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    public function login(Request $request)
    {
        $login = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        if( !Auth::attempt( $login )) {
            return response([
                'status_code' => '401',
                'message' => 'Invalid Credintials'
            ]);
        }

        $accessToken = Auth::user()->createToken('authToken')->accessToken;

        return response([
            'status_code' => 200,
            'user' => Auth::user(),
            'access_token' => $accessToken
        ]);
    }
}
