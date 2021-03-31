<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StageList
 *
 * @property int $id
 * @property int|null $step_number
 * @property bool|null $is_active
 * @property int|null $order_id
 * @property string $stage_name
 * @property int $status_id
 * @property bool|null $check
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|StageList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StageList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StageList query()
 * @method static \Illuminate\Database\Eloquent\Builder|StageList whereCheck($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StageList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StageList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StageList whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StageList whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StageList whereStageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StageList whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StageList whereStepNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StageList whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StageList extends Model
{
    use HasFactory;

    protected $fillable = [
        'step_number',
        'order_id',
        'stage_name',
        'status_id',
        'check',
        'is_active'
    ];
}
