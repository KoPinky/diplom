<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Request;

class PhotoService
{
    static private function Auth(): bool
    {
        return Auth::check();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $request
     *
     */
    public static function upload( $request)
    {
        if (!$request->hasFile('photo')) {
            return response()->json('Файл не найден', 400);
        }
        $user_id = Auth::id();
        $userPhoto = User::query()->findOrFail($user_id);
        $path = $request->file('photo')->move(public_path('images'));
        $userPhoto->photo = asset($path);
        $userPhoto->save();

        return $userPhoto->photo;
        //$userPhoto->photo;
//        $userPhoto->photo = Storage::url(storage_path('app/public/' . $path));
//        $userPhoto->save();
//        return ['url' => $userPhoto->photo, 'file' => response()->file(storage_path('app/public/' . $path))];

    }

    public static function destroy($photo): string
    {
        if (self::Auth()) {
            $user_id = Auth::id();
            $userPhoto = User::query()->findOrFail($user_id);
            $userPhoto->photo->delete();
            return 'Ok';
        }
        return 'Необходимо войти в профиль.';
    }
}
