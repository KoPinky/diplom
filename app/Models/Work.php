<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Work
 *
 * @property int $id
 * @property int $stage_id
 * @property string $work_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Work newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Work newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Work query()
 * @method static \Illuminate\Database\Eloquent\Builder|Work whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Work whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Work whereStageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Work whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Work whereWorkName($value)
 * @mixin \Eloquent
 */
class Work extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'stage_id',
        'work_name',
    ];
}
