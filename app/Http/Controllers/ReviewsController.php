<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Services\ReviewService;
use Exception;
use Illuminate\Http\JsonResponse;

class ReviewsController extends Controller
{

    /**
     * @param Request $request
     * @return false|JsonResponse|string
     */
    public function createReviews(Request $request)
    {
        return ReviewService::create($request);
    }

    /**
     * Display the specified resource.
     *
     * @param string $request
     * @return array|false|JsonResponse|string
     */
    public function show(string $request)
    {
        return ReviewService::show($request);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param Review $review
     * @return JsonResponse
     */
    public function edit(Request $request, Review $review): JsonResponse
    {
        return response()->json(ReviewService::editReview($request,$review), 201);
    }

    /**
     * @param Request $request
     * @param Review $review
     * @return JsonResponse
     * @throws Exception
     */
    public function delete(Request $request, Review $review): JsonResponse
    {
        return response()->json(ReviewService::delete($request,$review));
    }

    public function apply(Request $request, Review $review): JsonResponse
    {
        return response()->json(ReviewService::applyReview($request,$review));
    }

    public function reject(Request $request, Review $review): JsonResponse
    {
        return response()->json(ReviewService::delete($request, $review));
    }

    public function getNotApply(): JsonResponse
    {
        return ReviewService::getNotAcceptedReviews();
    }
}
