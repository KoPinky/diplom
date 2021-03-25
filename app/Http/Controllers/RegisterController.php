<?php

namespace App\Http\Controllers;

use App\Services\RegisterServices;
use Illuminate\Http\Request;

/**
 * Class RegisterController
 * @package App\Http\Controllers
 */
class RegisterController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function save(Request $request)
    {
        return RegisterServices::register($request);
    }

    public function refreshPassword(Request $request)
    {
        return RegisterServices::refreshPassword($request);
    }
}
