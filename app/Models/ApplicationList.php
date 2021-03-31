<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ApplicationList
 *
 * @property int $id
 * @property int $application_id
 * @property string $material
 * @property float $amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationList query()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationList whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationList whereApplicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationList whereMaterial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationList whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ApplicationList extends Model
{
    use HasFactory;

    protected $table = 'application_lists';

    protected $fillable = [
        'application_id',
        'material',
        'amount'
    ];
}
