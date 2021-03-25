<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ApplicationStatus
 *
 * @property int $id
 * @property string|null $status_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationStatus whereStatusName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ApplicationStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'status_name',
    ];
}
