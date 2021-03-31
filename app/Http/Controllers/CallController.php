<?php

namespace App\Http\Controllers;

use App\Exceptions\API\ValidateCall;

use App\Models\Call;
use Illuminate\Http\Request;

class CallController extends Controller
{
    public function callCreate(Request $request)
    {
        ValidateCall::validCall($request);

        Call::query()->create($request->only(['phone', 'name']));
        return response()->json(['message' => 'Ваша заявка принята']);
    }

    public function show() {
        return response()->json(['message' => 'Заявки на рассмотрение.',
            'data' => Call::all()]);
    }
}
