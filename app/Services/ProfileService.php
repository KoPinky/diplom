<?php


namespace App\Services;


use App\Exceptions\API\ValidateProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileService
{
    public static function show() {
        $id = Auth::id();
        return User::query()->findOrFail($id);
    }

    public static function editProfile(Request $request)
    {
        ValidateProfile::validUser($request);
        $user = $request->user();
        $input = $request->except('role_id');
        $user->update($input);

        if ($request->exists('password')) {
            $user->update([
                'password' => Hash::make($request['password'])
            ]);
        }
        return response()->json($user, 200);
    }
}
