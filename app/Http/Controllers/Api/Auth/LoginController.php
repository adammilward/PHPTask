<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;

class LoginController extends ApiController
{
    public function login(Request $request)
    {
        $creds = $request->only(['email', 'password']);
        $token = auth()->attempt($creds);
        return $token;
    }
}
