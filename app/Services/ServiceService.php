<?php

namespace App\Services;

use App\Exceptions\API\ValidateService;
use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

class ServiceService
{
    static public function store($request)
    {
        ValidateService::validService($request);

        $service = Service::query()->create($request->only(['service_name', 'price', 'description']));
        return $service->only(['id', 'service_name', 'price', 'description']);
    }

    /**
     * @return Builder[]|Collection
     */
    static public function index()
    {
        return Service::query()->select(['service_name', 'price', 'description'])->get();
    }

    /**
     * @param $service
     * @return mixed
     */
    static public function show($service)
    {
        return $service->only(['service_name', 'price', 'description']);
    }

    /**
     * @param $request
     * @param Service $service
     */
    static public function update($request, Service $service)
    {
        ValidateService::validService($request);

        $service->update($request->only(['service_name', 'price', 'description']));
        return $service->only(['service_name', 'price', 'description']);
    }
}
