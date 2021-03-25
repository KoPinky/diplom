<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Services\ServiceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class ServiceController
 * @package App\Http\Controllers
 */
class ServiceController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        return response()->json(ServiceService::store($request), 201);
    }

    /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(ServiceService::index());
    }

    /**
     * Display the specified resource.
     *
     * @param Service $service
     * @return JsonResponse
     */
    public function show(Service $service): JsonResponse
    {
        return response()->json(ServiceService::show($service));
    }

    /**
     * @param Service $service
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Service $service, Request $request): JsonResponse
    {
        return response()->json(ServiceService::update($request, $service), 202);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        Service::destroy($id);
        return response()->json('OK');
    }

    /**
     * @param Service $service
     * @return JsonResponse
     */
    public function getStages(Service $service): JsonResponse
    {
        return response()->json($service->stage()->get());
    }
}

