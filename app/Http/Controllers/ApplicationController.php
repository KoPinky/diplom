<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Services\ApplicationService;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json(ApplicationService::showApplication(Application::all()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        return ApplicationService::create($request);
    }

    /**
     * Display the specified resource.
     *
     * @param Application $application
     * @param int|null $id
     * @return array[]
     */
    public function show(Application $application, int $id = null)
    {
        return ApplicationService::showApplication($application, $id);
    }

    /**
     * @return Application|Application[]|Builder|Builder[]|Collection
     */
    public function showApplicationUser()
    {
        return ApplicationService::showApplicationUser();
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, int $id)
    {
        return response()->json(ApplicationService::update($id, $request))->getContent();
    }

    public function setStatus(Request $request, int $id)
    {
        return response()->json(ApplicationService::setStatusApplication($request, $id))->getContent();
    }

    public function getListStatus(Request $request)
    {
        return ApplicationService::getListStatus($request);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return false|string
     */
    public function addMaterial(Request $request, int $id)
    {
        return response()->json(ApplicationService::addMatereal($request, $id))->getContent();
    }

    public function updatePurchases(Request $request, int $id)
    {
        return response()->json(ApplicationService::updatePurchases($request, $id))->getContent();
    }


    public function removeMaterial(int $id)
    {
        return response()->json(ApplicationService::removeMatereal($id))->getContent();
    }

    public function delete(int $id)
    {
        return ApplicationService::delete($id);
    }

}
