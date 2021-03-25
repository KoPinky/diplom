<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class ObjectB
 *
 * @package App\Models
 * @property int $id
 * @property string $address
 * @property int|null $amountRoom
 * @property string|null $area
 * @property string|null $description
 * @property int $status_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ObjectB newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ObjectB newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ObjectB query()
 * @method static \Illuminate\Database\Eloquent\Builder|ObjectB whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ObjectB whereAmountRoom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ObjectB whereArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ObjectB whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ObjectB whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ObjectB whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ObjectB whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ObjectB whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ObjectB extends Model
{
    use HasFactory;
    protected $table = 'objects';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address',
        'amountRoom',
        'area',
        'description',
        'status_id'
    ];

    public function getOrder()
    {
        return $this->hasOne(Order::class, 'object_id', 'id');
    }
}
