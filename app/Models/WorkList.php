<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WorkList
 *
 * @property int $id
 * @property int $stage_list_id
 * @property string $work_name
 * @property int $step_number
 * @property bool $check
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|WorkList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkList query()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkList whereCheck($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkList whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkList whereStageListId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkList whereStepNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkList whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkList whereWorkId($value)
 * @mixin \Eloquent
 */
class WorkList extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'stage_list_id',
        'work_name',
        'step_number',
        'check',
        'date_start',
        'date_end'
    ];

}
