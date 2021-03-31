<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestStage;
use App\Models\ObjectB;
use App\Models\Order;
use App\Models\Stage;
use App\Models\StageList;
use App\Models\StageReports;
use App\Services\StageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Class StageController
 * @package App\Http\Controllers
 */
class StageController extends Controller
{
    /**
     * @param Request $request
     * @param ObjectB $object
     * @return JsonResponse
     */
    public function confirmStage(Request $request, ObjectB $object)
    {
        return response()->json(StageService::confirmStage($object, $request));
    }

    /**
     * @param Request $request
     * @param ObjectB $object
     * @return JsonResponse
     */
    public function rejectStage(Request $request, ObjectB $object)
    {
        return response()->json(StageService::RejectStage($object, $request));
    }

    /**
     * @param Request $request
     * @param ObjectB $object
     * @return JsonResponse
     */
    public function checkStage(Request $request, ObjectB $object)
    {
        return response()->json(StageService::checkStage($object));
    }

    public function create(Request $request, ObjectB $object)
    {
        return response()->json(StageService::create($request, $object));
    }

    public function update(Request $request, StageList $stageList)
    {
        return response()->json(StageService::update($request, $stageList));
    }

    public function getStages(ObjectB $object)
    {
        return response()->json(StageService::getStages($object));
    }

    public function getStage(StageList $stageList)
    {
        return response()->json(StageService::getStage($stageList));
    }

    public function deleteStage(StageList $stageList)
    {
        return response()->json(StageService::deleteStage($stageList));
    }

    public function writeComment(StageList $stageList, Request $request)
    {
        return response()->json(StageService::writeComment($stageList, $request));
    }

    public function getComments(StageList $stageList)
    {
        return response()->json(StageService::getComments($stageList));
    }

    public function createReports(Request $request, StageList $stageList)
    {
        return response()->json(StageService::createReport($request, $stageList));
    }

    public function showReports(StageList $stageList)
    {
        return response()->json(StageService::getReports($stageList));
    }

    public function setStatusReports(int $id, Request $request)
    {
        return StageService::setStatus($id, $request);
    }

    public function getAllReports()
    {
        return StageService::getAllReports();
    }
}
