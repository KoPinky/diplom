<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ServiceMaterial
 *
 * @property int $id
 * @property int $service_id
 * @property int $material_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceMaterial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceMaterial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceMaterial query()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceMaterial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceMaterial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceMaterial whereMaterialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceMaterial whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceMaterial whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ServiceMaterial extends Model
{
    use HasFactory;
    protected $table = 'service_materials';

    /**
     * @var array
     */
    protected $fillable = [
        'service_id',
        'material_id',
    ];
}
