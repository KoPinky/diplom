<?php

namespace App\Services;

use App\Models\ObjectB;
use App\Models\StageList;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ProgressTableService
{
    /**
     * @param ObjectB $object
     * @return bool|Builder|Model|int|object|null
     */
    public static function getTable(ObjectB $object)
    {
        $order = $object->getOrder()->first();
        $stage_list = StageList::query()
            ->select([
                'stage_lists.stage_id',
                'stages.stage_name',
                'stage_lists.step_number',
                'stage_lists.is_active',
                'stage_lists.date_start',
                'stage_lists.date_end'
            ])
            ->join('stages', 'stages.id', '=', 'stage_lists.stage_id')
            ->where('order_id', '=', $order->id)
            ->get();
        return $stage_list;
    }

    public static function setTable(ObjectB $object, Request $request)
    {
        $validated_data = $request->validate([
            'table.*.step_number' => 'required|integer',
            'table.*.date_start' => 'required|date',
            'table.*.date_end' => 'required|date',
        ]);
        $order = $object->getOrder()->first();
        foreach ($request->table as $stage) {
            $stage_list = StageList::query()
                ->where('order_id', '=', $order->id)
                ->where('step_number', '=', $stage['step_number'])
                ->first();
            $stage_list = $stage_list->update([
                'date_start' => $stage['date_start'],
                'date_end' => $stage['date_end'],
            ]);
        }
        return self::getTable($object);
    }
}
