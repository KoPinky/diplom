<?php

namespace App\Http\Controllers;

use App\Models\ObjectB;
use App\Models\Stage;
use App\Models\StageList;
use App\Models\WorkList;
use App\Services\WorkService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class WorkController extends Controller
{
    /**
     * @param ObjectB $object
     * @return JsonResponse
     */
    public function completeWork(ObjectB $object): JsonResponse
    {
        return response()->json(WorkService::completeWork($object));
    }

    public function create(Request $request,ObjectB $object)
    {
        return response()->json(WorkService::create($request, $object));
    }
    public function getWorksOfStage(StageList $stageList): JsonResponse
    {
        return response()->json(WorkService::getWorksOfStage($stageList));
    }
    public function editWork(Request $request,WorkList $workList): JsonResponse
    {
        return response()->json(WorkService::editWork($request,$workList));
    }
    public function deleteWork(WorkList $workList): JsonResponse
    {
        return response()->json(WorkService::deleteWork($workList));
    }
}
