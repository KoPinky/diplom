<?php

namespace App\Http\Controllers;

use App\Models\ApplicationStatus;
use App\Models\ObjectStatus;
use App\Models\StageStatus;
use App\Services\StageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function StageStatuses()
    {
        return response()->json(StageStatus::all(['id', 'status_name']));
    }

    /**
     * @return JsonResponse
     */
    public function ObjectStatuses()
    {
        return response()->json(ObjectStatus::all(['id', 'status_name']));
    }

    /**
     * @return JsonResponse
     */
    public function ApplicationStatuses()
    {
        return response()->json(ApplicationStatus::all(['id', 'status_name']));
    }


}
