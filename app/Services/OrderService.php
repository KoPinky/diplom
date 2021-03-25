<?php


namespace App\Services;


use App\Exceptions\API\ValidateOrder;
use App\Models\Order;

class OrderService
{
    static public function store($request): array
    {
        $valid = ValidateOrder::validOrder($request);
        if ($valid['bool'] == true) {
            return $valid['errors'];
        }
        $order = Order::query()->create($request->only([
            'date_contract', 'date_start',
            'date_end', 'status', 'object_id'
        ]));

        return self::show($order);
    }

    /**
     * @param Order $order
     * @return array
     */
    static public function show(Order $order): array
    {
        return $order->only([
            'id', 'date_contract', 'date_start',
            'date_end', 'status', 'object_id'
        ]);
    }

    /**
     * @param $request
     * @param Order $order
     * @return array
     */
    static public function update($request, Order $order): array
    {
        $valid = ValidateOrder::validOrder($request);
        if ($valid['bool'] == true) {
            return $valid['errors'];
        }
        $order->update($request->only([
            'date_contract', 'date_start',
            'date_end', 'status', 'object_id'
        ]));
        $order->save();
        return self::show($order->id);
    }
}
