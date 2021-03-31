<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WorkUser
 *
 * @property int $id
 * @property int $user_id
 * @property int $work_id
 * @property string $date_work
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|WorkUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkUser whereDateWork($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkUser whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkUser whereWorkId($value)
 * @mixin \Eloquent
 */
class WorkUser extends Model
{
    use HasFactory;


    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'work_list_id',
        'date_work'
    ];
}
