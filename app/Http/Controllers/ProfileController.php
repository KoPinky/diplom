<?php

namespace App\Http\Controllers;

use App\Services\ProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show() {
        return ProfileService::show();
    }

    public function editProfile(Request $request)
    {
        return ProfileService::editProfile($request);
    }
}
