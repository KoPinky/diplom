<?php

namespace App\Http\Controllers;

use App\Services\LoginService;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request) {
        return LoginService::login($request);
    }
}
