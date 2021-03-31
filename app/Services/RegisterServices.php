<?php


namespace App\Services;

use App\Exceptions\API\ValidateRegistration;
use App\Mail\UserPasswordRefreshed;
use App\Mail\UserRegistered;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterServices
{
    static public function register($request)
    {
        ValidateRegistration::validReg($request);
        $request['email'] = strtolower($request['email']);
        $user = new User($request->all());
        if ($request['password_auto'])
            $password = Str::random(8);
        else
            $password = $request['password'];
        $user->password = Hash::make($password);
        $user->save();

        Mail::to($user)->send(new UserRegistered($user, $password));

        return response()->json(
            ['message' => 'Пользователь успешно зарегистрирован.',
            'data' => $user], 201);
    }

    static public function refreshPassword(Request $request)
    {
        Validator::validate($request->all(),
            ['email' => 'required|string|email|exists:users'],
            ['email.exists' => 'Введенный e-mail не зарегистрирован.']);

        $user = User::whereEmail($request['email'])->first();
        $new_password = Str::random(8);
        $user->password = Hash::make($new_password);
        $user->save();

        Mail::to($user)->send(new UserPasswordRefreshed($user, $new_password));

        return response()->json(
            ['message' => 'Новый пароль был отправлен на указанную почту.'], 200);
    }
}
