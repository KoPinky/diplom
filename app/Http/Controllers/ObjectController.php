<?php

namespace App\Http\Controllers;

use App\Models\ObjectB;
use App\Services\ObjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class ObjectController
 * @package App\Http\Controllers
 */
class ObjectController extends Controller
{

    public function index()
    {
        return ObjectService::index();
    }


    /**
     * Display the specified resource.
     *
     * @param ObjectB $object
     * @return JsonResponse
     */
    public function show(ObjectB $object)
    {
        return response()->json(ObjectService::show($object));
    }

    public function my_objects()
    {
        return response()->json(ObjectService::my_objects());
    }

    public function store(Request $request)
    {
        return ObjectService::store($request);
    }

    public function update(Request $request, ObjectB $object)
    {
        return ObjectService::update($request, $object);
    }
}
