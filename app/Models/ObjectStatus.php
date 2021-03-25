<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ObjectStatus
 *
 * @property int $id
 * @property string|null $status_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ObjectStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ObjectStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ObjectStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|ObjectStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ObjectStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ObjectStatus whereStatusName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ObjectStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ObjectStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'status_name',
    ];
}
