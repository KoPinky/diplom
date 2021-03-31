<?php


namespace App\Services;

use App\Exceptions\API\ValidateApplication;
use App\Exceptions\API\ValidateDoc;
use App\Exceptions\API\ValidateLogin;
use App\Exceptions\API\ValidateObject;
use App\Exceptions\API\ValidateStage;
use App\Models\Document;
use App\Models\ObjectB;
use App\Models\Order;
use App\Models\Service;
use App\Models\Stage;
use App\Models\StageComment;
use App\Models\StageList;
use App\Models\StageReports;
use App\Models\WorkList;
use Facade\FlareClient\Report;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\Self_;

class StageService
{
    private static function user() {
        $user = Auth::user()->only([
            'id',
            'surname',
            'first_name',
            'second_name',
            'phone',
        ]);
        return $user;
    }

    /**
     * @param ObjectB $object
     * @param Request $request
     * @return string
     */
    public static function confirmStage(ObjectB $object, Request $request)
    {
        try {
            $request->validate([
                'text' => 'string'
            ]);
            $order = $object->getOrder()->firstOrFail();
            $stages_list = StageList::query()
                ->where('order_id', '=', $order->id)
                ->where('is_active', '=', true)
                ->firstOrFail();
            if ($stages_list->status_id == 1)
                return 'Этап еще не начат';
            if ($stages_list->status_id == 2)
                return 'Этап еще не готов';
            $stages_list->is_active = false;
            $stages_list->check = true;
            $stages_list->status_id = 4;
            $stages_list->save();
            $comment = (!is_null($request->input('text'))) ? StageComment::query()->create([
                'text' => $request->input('text'),
                'stage_list_id' => $stages_list->id,
                'user_id' => auth()->id()
            ]) : '';
            $next_stages_list = StageList::query()
                ->where('order_id', '=', 2)
                ->where('step_number', '=', $stages_list->step_number + 1)
                ->firstOrFail();
            $next_stages_list->is_active = true;
            $next_stages_list->status_id = 2;
            $next_stages_list->save();
            return 'OK';
        } catch (\Exception $exception) {
            return 'Нет активных этапов или все этапы выполнены';
        }
    }

    /**
     * @param ObjectB $object
     * @return string
     */
    public static function rejectStage(ObjectB $object, Request $request)
    {
        try {
            $request->validate([
                'text' => 'string'
            ]);
            $order = $object->getOrder()->firstOrFail();
            $stages_list = StageList::query()
                ->where('order_id', '=', $order->id)
                ->where('is_active', '=', true)
                ->firstOrFail();
            if ($stages_list->status_id == 1)
                return 'Этап еще не начат';
            if ($stages_list->status_id == 2)
                return 'Этап еще не готов';
            $comment = StageComment::query()->create([
                'text' => $request->input('text'),
                'stage_list_id' => $stages_list->id,
                'user_id' => auth()->id()
            ]);
            $stages_list->status_id = 2;
            $stages_list->save();
            return 'OK';
        } catch (\Exception $exception) {
            return 'Нет активных этапов или все этапы выполнены';
        }
    }

    /**
     * @param ObjectB $object
     * @return string
     */
    public static function checkStage(ObjectB $object)
    {
        try {
            $order = $object->getOrder()->firstOrFail();
            $stages_list = StageList::query()
                ->where('order_id', '=', $order->id)
                ->where('is_active', '=', true)
                ->firstOrFail();

            $works = WorkList::query()
                ->select([
                    'work_lists.id',
                    'work_lists.step_number',
                    'work_lists.work_name',
                ])
                ->where('stage_list_id', '=', $stages_list->id)
                ->where('check', '=', 'false')
                ->get();
            if (count($works) != 0) {
                return 'Не все работы выполнены!';
            }
            if ($stages_list->status_id == 4)
                return 'Этоп подтвержден';
            if ($stages_list->status_id == 1)
                return 'Этоп еще не начат';
            $stages_list->status_id = 3;
            $stages_list->save();
            return 'OK';

        } catch (\Exception $exception) {
            return 'Нет активных этапов или все этапы выполнены';
        }
    }

    /**
     * @param ObjectB $object
     * @param Request $request
     * @return array
     */
    public static function create(Request $request, ObjectB $object)
    {
        ValidateStage::validStage($request);
        $order = $object->getOrder()->first();
        $stepNumberMax = StageList::query()
            ->where('order_id', '=', $order->id)
            ->max('step_number');
        $s = 1;
        foreach ($request->stages as $stage) {
            $stagesa = StageList::query()
                ->Create([
                    'stage_name' => $stage['stage_name'],
                    'step_number' => $stepNumberMax + 1,
                    'order_id' => $order->id,
                    'status_id' => 1,
                    'is_active' => false,
                ]);
            $s++;
        }

        return self::getStages($object);
    }

    /**
     * @param Request $request
     * @param StageList $stageList
     * @return array
     */
    public static function update(Request $request, StageList $stageList)
    {
        if (count($request->all()) == 1) {
            return ['message' => 'Введите изменяемые данные'];
        }
        $stageList->update($request->all());
        if (count($stageList->getChanges()) == 0) {
            return ['message' => 'Обновляемые данные не изменены'];
        }
        $stageList->save();
        return self::getStage($stageList);
    }

    public static function getStages(ObjectB $object)
    {
        $sl = StageList::query()
            ->select([
                'id',
                'step_number',
                'stage_name',
                'status_id',
                'check',
                'is_active'
            ])
            ->where('order_id', '=', $object->getOrder()->first()->id)
            ->get()->toArray();
        $response = [];
        foreach ($sl as $stageList) {
            $works = WorkList::query()
                ->select([
                    'id',
                    'stage_list_id',
                    'work_name',
                    'step_number',
                    'date_start',
                    'date_end',
                    'check'
                ])
                ->where('stage_list_id', $stageList['id'])
                ->get();
            array_push($response, [
                'stage' => $stageList,
                'works' => $works
            ]);
        }
        return $response;
    }

    public static function getStage(StageList $stageList)
    {
        return $stageList->only(['id', 'stage_name', 'status_id', 'check', 'is_active', 'created_at', 'updated_at']);
    }

    public static function deleteStage(StageList $stageList)
    {
        $stageList->delete();
        return ["message" => "Этап удалён"];
    }

    public static function writeComment(StageList $stageList, Request $request)
    {
        ValidateStage::validComment($request);
        StageComment::query()->create([
            'text' => $request->text,
            'user_id' => auth()->id(),
            'stage_list_id' => $stageList->id
        ]);
        return self::getComments($stageList);
    }

    /**
     * @param StageList $stageList
     * @return StageComment[]|Builder[]|Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public static function getComments(StageList $stageList)
    {
        return StageComment::query()
            ->select([
                'users.surname',
                'users.first_name',
                'users.second_name',
                'stage_comments.text'
            ])
            ->join('users', 'users.id', '=', 'stage_comments.user_id')
            ->where('stage_list_id', $stageList->id)
            ->get();
    }

    public static function createReport(Request $request, StageList $stageList)
    {

        ValidateStage::validComment($request);
        $input_data = $request->all();
        if (!isset($request->file)) {
            return json_decode(response()->json(['errors' => "Введите корректное название(file[...])"], 400)->getContent());
        }
        if (count($request->all()) != 1) {
            $validate_count = ValidateDoc::customCountValidation(count($request->file));
            if ($validate_count != null) {
                return $validate_count;
            }
        }
        $answer = [];
        $description = [];
        ValidateDoc::validateDocument($input_data);
        foreach ($request->file as $file) {
            $path = $file->store('reports', 'public');
            $document = Document::query()->create([
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'object_id' => $request->object_id,
                'mime' => $file->getMimeType(),
                'size' => $file->getSize()]);

            $result = StageReports::query()->create([
                'text' => $request['text'],
                'status' => 'active',
                'stage_list_id' => $stageList->id,
                'document_id' => $document->id,
                'user_id' => self::user()['id'],
            ]);
            array_push($description, $result);
            array_push($answer, [
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'mime' => $file->getMimeType(),
                'size' => $file->getSize()
            ]);
        }

        return ['documents' => $answer, 'description' => $description,'user' => self::user()];
    }

    public static function getReports(StageList $stageList)
    {
        $reports = StageReports::query()->where('stage_list_id', $stageList->id)->get();
        $response = [];
        $documents = [];
        $repeated_report_one = null;
        foreach ($reports->unique('text') as $report) {
            $repeated_reports = $reports->where('text', $report->text);
            $repeated_report_one = $repeated_reports->first();
            foreach ($repeated_reports as $repeated_report) {
                $documents_arr = Document::query()->findOrFail($repeated_report->document_id);
                array_push($documents, $documents_arr);
            }
            array_push($response, [
                'description' => $repeated_report_one,
                'document' => $documents,
                'user' => self::user()
            ]);
            $documents = array();
        }

        return $response;
    }

    public static function setStatus($id, $request)
    {
        $reports = StageReports::query()->findOrFail($id);
        $reports->update([
            'status' => $request->status
        ]);
        return $reports;
    }

    public static function getAllReports()
    {
        $answer = [];
        $all_reports = StageReports::query()->where('status', '=', 'active')->get()->all();
        foreach ($all_reports as $report) {
            array_push($answer, [
                'reports' => $report,
                'document' => Document::query()->where('object_id', '=', $report['object_id'])->get()
            ]);
        }
        return $answer;
    }
}
