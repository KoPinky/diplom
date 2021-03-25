<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StageStatus
 *
 * @property int $id
 * @property string|null $status_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|StageStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StageStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StageStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|StageStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StageStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StageStatus whereStatusName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StageStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StageStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'status_name',
    ];
}
