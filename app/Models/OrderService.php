<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrderService
 *
 * @method static \Illuminate\Database\Eloquent\Builder|OrderService newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderService newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderService query()
 * @mixin \Eloquent
 */
class OrderService extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'service_id',
        'status_id',
        'price'
    ];
}
