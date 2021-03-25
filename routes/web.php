<?php

use App\Http\Controllers\CallController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ObjectController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkController;
use App\Models\ApplicationStatus;
use App\Models\ObjectStatus;
use App\Models\StageStatus;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('/six/backend/')->group(function () {

    /**
     * Чтобы не мучить наставника с миграциями.
     */
    Route::get('/refresh', function () {
        Artisan::call('migrate:refresh');
        return "Cache is cleared";
    });

    Route::get('/link', function () {
        Artisan::call('storage:link');
        return "Good.";
    });

    /**
     * Маршрут "обратная связь"
     */
    Route::post('call', [CallController::class, 'callCreate'])->name('call');
    Route::get('call', [CallController::class, 'show'])
        ->middleware('role:1');

    /**
     * авторизации/аутентификации
     */
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/refresh_password', [RegisterController::class, 'refreshPassword']);
    /**
     * Маршрут вывода отзывов
     */
    Route::get('/reviews/{one}', [ReviewsController::class, 'show']);

    Route::middleware(['auth:web'])->group(function () {
        /**
         * Маршруты регистрации
         */
        Route::post('/registration', [RegisterController::class, 'save'])
            ->middleware('role:1')->name('registration');

        /**
         * ..........................
         * Маршруты документооборота
         * ..........................
         */
        Route::post('/document', [DocumentController::class, 'docCreate'])
            ->name('document');
        Route::get('/documents', [DocumentController::class, 'docsShow'])
            ->name('documents');
        Route::get('/document/{id}', [DocumentController::class, 'docShow']);
        Route::get('/document/download/{id}', [DocumentController::class, 'docDownload']);
        Route::delete('/document/{id}', [DocumentController::class, 'docDelete']);

        /**
         * Маршруты отзывов
         */
        Route::post('/reviews', [ReviewsController::class, 'createReviews']);

        Route::put('/reviews/{review}', [ReviewsController::class, 'edit']);

        Route::delete('/reviews/{review}', [ReviewsController::class, 'delete']);

        Route::put('/reviews/apply/{review}', [ReviewsController::class, 'apply'])
            ->middleware('role:1');

        Route::delete('/reviews/reject/{review}', [ReviewsController::class, 'reject'])
            ->middleware('role:1');

        Route::get('reviews/show/creation', [ReviewsController::class, 'getNotApply'])
            ->middleware('role:1');

        /**
         * Маршруты пользователя
         */
        Route::post('/userPhoto', [UserController::class, 'photoCreate']);
        Route::delete('/userPhoto', [UserController::class, 'photoDelete']);
        Route::get('/user/{user}', [UserController::class, 'getUser'])
            ->middleware('role:1');
        Route::put('/user/{user}', [UserController::class, 'editUser'])
            ->middleware('role:1');
        Route::delete('/user/{user}', [UserController::class, 'deleteUser'])
            ->middleware('role:1');

        /**
         * Маршруты профиля
         */
        Route::put('/profile', [ProfileController::class, 'editProfile']);
        Route::get('/profile', [ProfileController::class, 'show']);

        /**
         * Маршруты service
         */
        Route::get('service', [ServiceController::class, 'index']);//+
        Route::get('service/{service}', [ServiceController::class, 'show']);//+
        Route::post('service', [ServiceController::class, 'store']);//+
        Route::post('service/{service}', [ServiceController::class, 'update']);//+
        Route::delete('service/{id}', [ServiceController::class, 'destroy']);//+
        Route::get('service/{service}/stages', [ServiceController::class, 'getStages']);//+

        /**
         * Маршруты stage
         */
        Route::post('object/{object}/confirmStage', [StageController::class, 'ConfirmStage']);
        Route::post('object/{object}/rejectStage', [StageController::class, 'RejectStage']);
        Route::post('object/{object}/checkStage', [StageController::class, 'CheckStage']);
        Route::post('/stage/{object}', [StageController::class, 'create']);
        Route::put('/stage/edit/{stage_list}', [StageController::class, 'update']);
        Route::get('/stage/{object}', [StageController::class, 'getStages']);
        Route::get('/stage/show/{stage_list}', [StageController::class, 'getStage']);
        Route::delete('/stage/delete/{stage_list}', [StageController::class, 'deleteStage']);
        Route::post('/stage/create/reports/{id}', [StageController::class, 'createReports'])
            ->middleware('role:3');
        Route::get('/stage/show/reports/{id}', [StageController::class, 'showReports'])
            ->middleware('role:4');
        Route::post('/stage/setStatus/reports/{id}', [StageController::class, 'setStatusReports'])
            ->middleware('role:4');
        Route::get('/reports/all', [StageController::class, 'getAllReports'])
            ->middleware('role:4');
        /**
         * Маршруты подъэтапов
         */
        Route::post('object/{object}/completeWork', [WorkController::class, 'completeWork']);
        Route::post('/work/{object}', [WorkController::class, 'create']);
        Route::get('/works/{stage_list}', [WorkController::class, 'getWorksOfStage']);
        Route::put('/works/edit/{work_list}', [WorkController::class, 'editWork']);
        Route::delete('/works/delete/{work_list}', [WorkController::class, 'deleteWork']);


        /**
         * Маршруты object
         */
        Route::post('/object', [ObjectController::class, 'store']);
        Route::get('/objects', [ObjectController::class, 'index']);
        Route::get('/object/{object}', [ObjectController::class, 'show']);
        Route::put('/object/{object}', [ObjectController::class, 'update']);

        /**
         * статусы
         */
        Route::get('/statuses/object', function () {
            return response()->json(ObjectStatus::all(['id', 'status_name']));
        });
        Route::get('/statuses/application', function () {
            return response()->json(ApplicationStatus::all(['id', 'status_name']));
        });
        Route::get('/statuses/stage', function () {
            return response()->json(StageStatus::all(['id', 'status_name']));
        });

        /**
         * Модуль закупок
         */
        Route::post('/application', [ApplicationController::class, 'store']);
        Route::get('/application', [ApplicationController::class, 'index']);
        Route::get('/application/{id}', [ApplicationController::class, 'show']);
        Route::get('/getUser', [ApplicationController::class, 'showApplicationUser']);
        Route::post('/application/{id}/list', [ApplicationController::class, 'addMaterial']);
        Route::put('/application/{id}/list', [ApplicationController::class, 'updatePurchases']);
        Route::delete('/application/{id}/list', [ApplicationController::class, 'removeMaterial']);
        Route::post('/application/{id}/status', [ApplicationController::class, 'setStatus'])->middleware('role:4');
        Route::post('/application/{id}/upload', [ApplicationController::class, 'update']);
        Route::delete('/application/{id}', [ApplicationController::class, 'delete']);
        Route::get('/application/get/status', [ApplicationController::class, 'getListStatus']);

        /**
         * Пользователи по ролям
         */
        Route::get('/foremen', [UserController::class, 'getForemen']);
        Route::get('/customer', [UserController::class, 'getCustomer']);
        Route::get('/performer', [UserController::class, 'getPerformer']);

        Route::get('/comments/{stage_list}', [StageController::class, 'getComments']);
        Route::post('/comments/{stage_list}', [StageController::class, 'writeComment']);
    });
});


