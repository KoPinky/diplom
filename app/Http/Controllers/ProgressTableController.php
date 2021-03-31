<?php

namespace App\Http\Controllers;

use App\Models\ObjectB;
use App\Services\ProgressTableService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProgressTableController extends Controller
{
    /**
     * @param ObjectB $object
     * @return JsonResponse
     */
    public function getTable(ObjectB $object)
    {
        return response()->json(ProgressTableService::getTable($object));
    }

    public function setTable(ObjectB $object, Request $request)
    {
        return response()->json(ProgressTableService::setTable($object, $request));
    }
}
