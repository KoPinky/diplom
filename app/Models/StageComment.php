<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StageComment
 *
 * @property int $id
 * @property string $text
 * @property int $stage_list_id
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|StageComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StageComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StageComment query()
 * @method static \Illuminate\Database\Eloquent\Builder|StageComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StageComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StageComment whereStageListId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StageComment whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StageComment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StageComment whereUserId($value)
 * @mixin \Eloquent
 */
class StageComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'stage_list_id',
        'user_id'
    ];
}
