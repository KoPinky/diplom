<?php


namespace App\Services;

use App\Exceptions\API\ValidateReviews;
use App\Models\Review;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function React\Promise\all;


class ReviewService
{
    /**
     * @return int|string|null
     */
    static protected function id()
    {
        return Auth::id();
    }

    /**
     * @param $request
     * @return bool
     */
    static protected function auth($request): bool
    {
        $result = $request['user_id'];
        if ($result == self::id()) {
            return true;
        }
        return false;
    }

    /**
     * @param $request
     * @return array|false|Builder|Model|string
     */
    static public function create($request)
    {
        if (Auth::check()) {
            ValidateReviews::validReview($request);

            $review = Review::query()->create(['text' => $request['text'], 'user_id' => self::id()]);
            $created_reviews = Review::query()
                ->select(['reviews.id', 'users.first_name', 'users.surname', 'users.second_name', 'reviews.text', 'reviews.created_at'])
                ->join('users', 'users.id', '=', 'reviews.user_id')
                ->where('reviews.id', '=', $review->id)->get();
            return response()->json(["message" => "Отзыв отправлен на модерацию", "data" => $created_reviews]);
        }
        return 'необходимо войти в систему';
    }

    static public function applyReview(Request $request, Review $review)
    {
        $review->update([
            'status' => 'apply'
        ]);

        return $review;
    }

    /**
     * @param $request
     * @return array|false|string
     */
    static public function show($request)
    {
        $i = 0;
        $answer = array();
        switch ($request) {
            case "all":
                $reviews = Review::query()
                    ->select(['reviews.id', 'users.first_name', 'users.surname', 'users.second_name', 'reviews.text', 'reviews.created_at'])
                    ->join('users', 'users.id', '=', 'reviews.user_id')
                    ->where('status', '!=', 'create')
                    ->get();
                return response()->json(["data" => $reviews]);
            case "one":
                $reviews = Review::query()
                    ->select(['reviews.id', 'users.first_name', 'users.surname', 'users.second_name', 'reviews.text', 'reviews.created_at'])
                    ->join('users', 'users.id', '=', 'reviews.user_id')
                    ->where('status', '!=', 'create')
                    ->limit(5)
                    ->get();
                return response()->json(["data" => $reviews]);
        }
    }

    /**
     * @param Request $request
     * @param Review $review
     * @return array|Builder|Builder[]|Collection|Model|string|null
     */
    static public function editReview(Request $request, Review $review)
    {
        $user = Auth::user();
        if ($user->id == $review->user_id || $user->role_id == 1) {
            ValidateReviews::validReview($request);

            $review->update(array('text' => $request['text']));
            $review->save();
            return response()->json(["message" => "Отзыв изменён", "data" => $review->only(['text', 'user_id', 'id'])]);
        }

        return response()->json(["error" => "Вы не являетесь создателем"]);
    }

    /**
     * @param Request $request
     * @param Review $review
     * @return string
     * @throws Exception
     */
    static public function delete(Request $request, Review $review)
    {
        $user = Auth::user();
        if ($user->id == $review->user_id || $user->role_id == 1) {
            $review->delete();
            return response()->json(["message" => "Отзыв удалён"]);
        }
        return response()->json(["error" => "Вы не являетесь создателем"]);
    }

    static public function getNotAcceptedReviews(): JsonResponse
    {
        $reviews = Review::query()
            ->select(['reviews.id', 'users.first_name', 'users.surname', 'users.second_name', 'reviews.text', 'reviews.created_at'])
            ->join('users', 'users.id', '=', 'reviews.user_id')
            ->where('status', '=', 'create')
            ->get();

        return response()->json(["message" => "Непринятые отзывы", "data" => $reviews]);
    }

}
