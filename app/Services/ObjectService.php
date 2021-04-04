<?php


namespace App\Services;

use App\Exceptions\API\ValidateObject;
use App\Models\Document;
use App\Models\ObjectB;
use App\Models\Order;
use App\Models\Stage;
use App\Models\StageList;
use App\Models\UserOrder;
use App\Models\WorkList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ObjectService
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
     * @param $request
     * @return array|string
     */
    static public function store(Request $request)
    {
        ValidateObject::validObj($request);
        $validated_data = $request->toArray();
        $request['status_id'] = 1;
        $object = ObjectB::query()->create($request->all());
        $request['object_id'] = $object->id;
        $order = Order::query()->create($request->all());
        $request['order_id'] = $order->id;
        foreach ($validated_data['users'] as $user) {
            UserOrder::query()->create([
                'user_id' => $user,
                'order_id' => $order->id
            ]);
        }
        $stage = StageList::query()->create([
            'step_number' => 1,
            'order_id' => $order->id,
            'stage_name' => 'Инициализация объекта',
            'status_id' => 2,
            'check' => false,
            'is_active' => true
        ]);
        return response()->json([
            'message' => 'Новый объект успешно создан!',
            'id' => $object->id
        ])->getContent();
    }

    public static function update(Request $request, ObjectB $object)
    {
        ValidateObject::validObj($request);
        $object->update($request->all());
        $order = Order::query()
            ->where('object_id', '=', $object->id)
            ->first();
        $order->update($request->all());
        UserOrder::query()->
        where('order_id', '=', $order->id)
            ->delete();
        foreach ($request->users as $user) {
            UserOrder::query()->create([
                'user_id' => $user,
                'order_id' => $order->id
            ]);
        }
        return self::show($object);
    }

    static public function show(ObjectB $object)
    {
        $order = Order::query()
            ->where('object_id', '=', $object->id)
            ->firstOrFail();
        $users_list = UserOrder::query()
            ->select(['users.id', 'roles.role'])
            ->join('users', 'users.id', 'user_orders.user_id')
            ->join('roles', 'roles.id', 'users.role_id')
            ->where('user_orders.order_id', '=', $order->id)
            ->get();
        $stages = [];
        $stages_list = StageList::query()
            ->select(['stage_lists.id',
                'stage_lists.step_number',
                'stage_lists.stage_name',
                'stage_lists.status_id',
                'stage_statuses.status_name',
                'stage_lists.check'
            ])
            ->join('stage_statuses', 'stage_statuses.id', '=', 'stage_lists.status_id')
            ->where('order_id', '=', $order->id)
            ->get();
        foreach ($stages_list as $stage) {
            $work = WorkList::query()
                ->select([
                    'work_lists.id',
                    'work_lists.step_number',
                    'work_lists.work_name',
                    'work_lists.date_start',
                    'work_lists.date_end',
                    'work_lists.check'
                ])
                ->where('stage_list_id', '=', $stage->id)
                ->get();
            array_push($stages, [
                'stage' => $stage,
                'works' => $work
            ]);
        }

        return [
            'object_id' => $object->id,
            'service_id' => $order->service_id,
            'address' => $object->address,
            'amountRoom' => $object->amountRoom,
            'area' => $object->area,
            'description' => $object->description,
            'date_start' => $order->date_start,
            'date_end' => $order->date_end,
            'users' => $users_list,
            'stages' => $stages
        ];
    }

    static public function my_objects()
    {
        $object = ObjectB::query()
            ->select([
                'objects.id',
                'objects.address',
                'services.service_name',
                'stage_lists.stage_name',
                'stage_statuses.status_name'])
            ->join('orders', 'objects.id', '=', 'orders.object_id')
            ->join('user_orders', 'orders.id', '=', 'user_orders.order_id')
            ->join('stage_lists', 'orders.id', '=', 'stage_lists.order_id')
            ->join('services', 'orders.service_id', '=', 'services.id')
            ->join('stage_statuses', 'stage_statuses.id', '=', 'stage_lists.status_id')
            ->where('objects.status_id', '=', 1)
            ->where('stage_lists.is_active', '=', true)
            ->where('user_orders.user_id','=',self::user()['id'])
            ->get();
        return $object;
    }


    static public function index()
    {
        $object = ObjectB::query()
            ->select([
                'objects.id',
                'objects.address',
                'services.service_name',
                'stage_lists.stage_name',
                'stage_statuses.status_name'])
            ->join('orders', 'objects.id', '=', 'orders.object_id')
            ->join('stage_lists', 'orders.id', '=', 'stage_lists.order_id')
            ->join('services', 'orders.service_id', '=', 'services.id')
            ->join('stage_statuses', 'stage_statuses.id', '=', 'stage_lists.status_id')
            ->where('objects.status_id', '=', 1)
            ->where('stage_lists.is_active', '=', true)
            ->get();
        return $object;
    }
}
