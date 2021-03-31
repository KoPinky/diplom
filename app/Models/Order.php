<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Order
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon $date_start
 * @property \Illuminate\Support\Carbon $date_end
 * @property int $service_id
 * @property float|null $price
 * @property int $object_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Service[] $service
 * @property-read int|null $service_count
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDateEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDateStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereObjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    /**
     * @var array
     */
    protected $fillable = [
        'date_start',
        'date_end',
        'service_id',
        'price',
        'object_id',
    ];

    protected $dates = [
        'date_contract',
        'date_start',
        'date_end'
    ];

    /**
     * @return BelongsToMany
     */
    public function service(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, OrderService::class)
            ->withPivot('status_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function getObject(): BelongsTo
    {
        return $this->belongsTo(ObjectB::class, 'object_id', 'id');
    }

}
