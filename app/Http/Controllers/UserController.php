<?php

namespace App\Http\Controllers;

use App\Exceptions\API\ValidateUser;
use App\Models\User;
use App\Services\PhotoService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{


    public function photoCreate(Request $request)
    {
        $file = PhotoService::upload($request);
        return $file;
    }

    /**
     * @param Request $requestPhoto
     * @return JsonResponse
     */
    public function photoDelete(Request $requestPhoto): JsonResponse
    {
        $photo = PhotoService::destroy($requestPhoto);
        return response()->json($photo);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function getUser(User $user)
    {
        return response()->json($user);
    }

    /**
     * Редактирование юзера админом
     *
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function editUser(Request $request, User $user)
    {
        ValidateUser::validUser($request);
        $user->update($request->all());

        if ($request->exists('password')) {
            $user->update([
                'password' => Hash::make($request['password'])
            ]);
        }
        return response()->json($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function deleteUser(User $user): JsonResponse
    {
        $user->delete();
        return response()->json('Пользователь успешно удалён', 200);
    }

    /**
     * @return JsonResponse
     */
    public function getForemen()
    {
        $users = User::query()
            ->select([
                'id',
                'surname',
                'first_name',
                'second_name',
            ])
            ->where('role_id', '=', 4)
            ->get();
        return response()->json($users);
    }

    /**
     * @return JsonResponse
     */
    public function getCustomer()
    {
        $users = User::query()
            ->select([
                'id',
                'surname',
                'first_name',
                'second_name',
            ])
            ->where('role_id', '=', 2)
            ->get();
        return response()->json($users);
    }

    /**
     * @return JsonResponse
     */
    public function getPerformer()
    {
        $users = User::query()
            ->select([
                'id',
                'surname',
                'first_name',
                'second_name',
            ])
            ->where('role_id', '=', 3)
            ->get();
        return response()->json($users);
    }
}
