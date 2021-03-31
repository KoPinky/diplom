<?php

namespace App\Services;

use App\Exceptions\API\ValidateWork;
use App\Models\ObjectB;
use App\Models\Stage;
use App\Models\StageList;
use App\Models\WorkList;
use Illuminate\Http\Request;

class WorkService
{
    /**
     * @param Request $request
     * @param WorkList $workList
     * @return string
     */
    public static function completeWork(Request $request,WorkList $workList): string
    {
        $request->validate([
            'value' => 'required|boolean'
            ]
        );
            $workList->check = $request->value;
            $workList->save();
            return 'OK';
    }

    public static function getWorksOfStage(StageList $stageList)
    {
        $work_list = WorkList::query()
            ->where('stage_list_id','=',$stageList->id)->get();
        return ['message'=>'Подэтапы этапа','data'=>$work_list];
    }

    public static function editWork(Request $request,WorkList $workList)
    {
        if(count($request->all()) == 1){
            return ['message'=>'Введите изменяемые данные'];
        }
        $workList->update($request->all());
        if(count($workList->getChanges()) == 0){
            return ['message'=>'Обновляемые данные не изменены'];
        }
        $workList->save();
        return ['message'=>'Подэтап изменён','data'=>$workList];
    }
    public static function deleteWork(WorkList $workList)
    {
        $workList->delete();
        return ["message"=>"Подэтап удалён"];
    }
    public static function create(Request $request,ObjectB $object)
    {
        $order = $object->getOrder()->first();
        $request['date_start_object'] = $order->date_start;
        $request['date_end_object'] = $order->date_end;
        ValidateWork::validWork($request);

        $stepNumberMax = WorkList::query()
            ->join('stage_lists','stage_lists.id','=','work_lists.stage_list_id')
            ->where('stage_lists.order_id', '=', $order->id)
            ->max('work_lists.step_number');
        $data = [];
        foreach ($request->works as $work) {
            $work_list = WorkList::query()
                ->Create([
                    'stage_list_id' => $work['stage_list_id'],
                    'work_name' => $work['work_name'],
                    'step_number' => $stepNumberMax+1,
                    'date_start' => $work['date_start'],
                    'date_end' => $work['date_end'],
                ]);
            array_push($data,$work_list);
        }
        return ["message"=>"Подэтапы успешно добавлены","data"=>$data];
    }
}

