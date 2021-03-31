<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Service
 *
 * @property int $id
 * @property string|null $serviceName
 * @property string|null $price
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $order
 * @property-read int|null $order_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Stage[] $stage
 * @property-read int|null $stage_count
 * @method static \Illuminate\Database\Eloquent\Builder|Service newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Service newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Service query()
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereServiceName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Service extends Model
{
    use HasFactory;

    protected $table = 'services';

    /**
     * @var array
     */
    protected $fillable = [
        'service_name',
        'price',
        'description'
    ];

    public function order()
    {
        return $this->belongsToMany(Order::class);
    }

    /**
     * @return HasMany
     */
    public function stage()
    {
        return $this->hasMany(Stage::class, 'service_id', 'id');
    }
}
