<?php

namespace App\Services;

use App\Exceptions\API\ValidateLogin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginService
{

    /**
     * @param $request
     * @return array|false|string
     */
    static public function login(Request $request)
    {
        ValidateLogin::validLog($request);
        $request['email'] = strtolower($request['email']);
        $credentials = $request->only('email', 'password');
        $token = auth()->attempt($credentials);

        if (!$token) {
            return response()->json(
                ['message' => 'Не удалось авторизироваться.'], 400);
        }

        return response()->json([
            'message' => 'Успешная авторизация.',
            'token' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 1,
                'user' => auth()->user()
            ],
        ]);
    }


}
