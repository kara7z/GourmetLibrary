<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiLoginRequest;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponses;

    function Login(ApiLoginRequest $request)
    {
        return $this->ok($request->get('email'));
    }
    function register()
    {
        return $this->ok('register ff');
    }
}
